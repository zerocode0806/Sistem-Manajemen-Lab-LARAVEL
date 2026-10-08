<?php

namespace App\Services;

use App\Models\DataPinjam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class LabReservationAvailability
{
    /**
     * Get booked seats for a lab and a date-time interval.
     *
     * A whole-lab reservation (or a legacy lab reservation without a seat)
     * blocks every seat for the overlapping interval.
     */
    public static function takenSeats(
        string $labName,
        string $startDate,
        string $startTime,
        string $endDate,
        string $endTime,
        int $totalSeats,
    ): array {
        $reservations = self::overlappingReservations(
            $labName,
            $startDate,
            $startTime,
            $endDate,
            $endTime,
        )->get(['kursi', 'seluruh_lab']);

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

    /**
     * Check whether a requested seat or whole-lab reservation is available.
     */
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

        if ($seat === null) {
            return false;
        }

        return !$query->where(function (Builder $query) use ($seat) {
            $query->where('seluruh_lab', true)
                ->orWhereNull('kursi')
                ->orWhere('kursi', $seat);
        })->exists();
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
