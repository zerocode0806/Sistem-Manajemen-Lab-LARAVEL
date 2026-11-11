<?php

namespace App\Services;

use App\Models\DataLab;
use App\Models\DataPinjam;
use App\Models\InventarisMeja;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class LabReservationAvailability
{
    /**
     * Get occupied seats for a lab and date/time interval.
     * Reservations covering the whole lab block every numbered seat.
     */
    public static function takenSeats(
        string $labName,
        string $startDate,
        string $startTime,
        string $endDate,
        string $endTime,
        int $totalSeats,
    ): array {
        $reservations = self::overlappingReservations($labName, $startDate, $startTime, $endDate, $endTime)
            ->get(['kursi', 'seluruh_lab']);

        $wholeLabBlocked = $reservations->contains(
            fn (DataPinjam $reservation) => (bool) $reservation->seluruh_lab || $reservation->kursi === null
        );

        if ($wholeLabBlocked) {
            return $totalSeats > 0 ? range(1, $totalSeats) : [];
        }

        return $reservations->pluck('kursi')
            ->filter(fn ($seat) => $seat !== null)
            ->map(fn ($seat) => (int) $seat)
            ->unique()
            ->values()
            ->all();
    }

    /** Return missing or faulty PC seat numbers for display in the seat picker. */
    public static function unavailableSeats(string $labName, int $totalSeats): array
    {
        if ($totalSeats <= 0) {
            return [];
        }

        $lab = DataLab::where('nama_lab', $labName)->first();
        if (!$lab) {
            return range(1, $totalSeats);
        }

        $pcs = InventarisMeja::where('id_lab', $lab->id_lab)
            ->whereBetween('nomor_meja', [1, $totalSeats])
            ->get(array_merge(['nomor_meja'], self::conditionFields()));

        $usableSeatNumbers = [];
        foreach ($pcs as $pc) {
            if (self::hasOnlyNormalConditions($pc)) {
                $usableSeatNumbers[] = (int) $pc->nomor_meja;
            }
        }

        // A seat with no linked workstation row is not a bookable PC seat.
        return array_values(array_diff(range(1, $totalSeats), $usableSeatNumbers));
    }

    /** Check whether a seat or whole-lab reservation is available. */
    public static function isAvailable(
        string $labName,
        string $startDate,
        string $startTime,
        string $endDate,
        string $endTime,
        ?int $seat,
        bool $wholeLab,
        ?int $ignoreReservationId = null,
    ): bool {
        $query = self::overlappingReservations($labName, $startDate, $startTime, $endDate, $endTime);

        if ($ignoreReservationId !== null) {
            $query->where('id_data', '!=', $ignoreReservationId);
        }

        if ($wholeLab) {
            return !$query->exists();
        }

        if ($seat === null || !self::isSeatOperational($labName, $seat)) {
            return false;
        }

        return !$query->where(function (Builder $query) use ($seat) {
            $query->where('seluruh_lab', true)
                ->orWhereNull('kursi')
                ->orWhere('kursi', $seat);
        })->exists();
    }

    private static function isSeatOperational(string $labName, int $seat): bool
    {
        $lab = DataLab::where('nama_lab', $labName)->first();
        if (!$lab) {
            return false;
        }

        $pc = InventarisMeja::where('id_lab', $lab->id_lab)
            ->where('nomor_meja', $seat)
            ->first(array_merge(['nomor_meja'], self::conditionFields()));

        return $pc !== null && self::hasOnlyNormalConditions($pc);
    }

    private static function conditionFields(): array
    {
        return [
            'cpu_status', 'monitor_status', 'keyboard_status', 'mouse_status', 'windows_status',
            'cpu_kondisi', 'monitor_kondisi', 'keyboard_kondisi', 'mouse_kondisi', 'kursi_kondisi',
        ];
    }

    private static function hasOnlyNormalConditions(InventarisMeja $pc): bool
    {
        foreach (self::conditionFields() as $field) {
            if (($pc->{$field} ?? null) !== 'normal') {
                return false;
            }
        }
        return true;
    }

    private static function overlappingReservations(
        string $labName,
        string $startDate,
        string $startTime,
        string $endDate,
        string $endTime,
    ): Builder {
        $requestedStart = "{$startDate} {$startTime}:00";
        $requestedEnd = "{$endDate} {$endTime}:00";

        $query = DataPinjam::query()
            ->where('nama_lab', $labName)
            ->where('jenis', 'lab')
            ->whereIn('status', ['menunggu', 'disetujui']);

        if (DB::connection()->getDriverName() === 'sqlite') {
            return $query->whereRaw(
                "datetime(`tanggal` || ' ' || `jam_mulai`) < datetime(?) AND datetime(COALESCE(`tanggal_selesai`, `tanggal`) || ' ' || `jam_selesai`) > datetime(?)",
                [$requestedEnd, $requestedStart],
            );
        }

        return $query->whereRaw(
            'TIMESTAMP(`tanggal`, `jam_mulai`) < ? AND TIMESTAMP(COALESCE(`tanggal_selesai`, `tanggal`), `jam_selesai`) > ?',
            [$requestedEnd, $requestedStart],
        );
    }
}
