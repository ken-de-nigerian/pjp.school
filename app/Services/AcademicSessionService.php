<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AcademicSession;
use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * Replicates legacy Settings::getSessions (list). Adds create, update, activate (set as current session).
 */
class AcademicSessionService
{
    /** Legacy: getSessions — list all. Returns collection of AcademicSession (year). */
    public function list(): Collection
    {
        return AcademicSession::query()->orderBy('year')->get();
    }

    public function find(int $id): ?AcademicSession
    {
        return AcademicSession::query()->find($id);
    }

    public function create(string $year): AcademicSession
    {
        return AcademicSession::query()->create(['year' => $year]);
    }

    public function update(int $id, string $year): int
    {
        return (int) AcademicSession::query()->where('id', $id)->update(['year' => $year]);
    }

    public function delete(int $id): int
    {
        return (int) AcademicSession::query()->where('id', $id)->delete();
    }

    /** Set this session as the current one (settings.session = year). */
    public function activate(int $id): bool
    {
        $session = $this->find($id);
        if ($session === null) {
            return false;
        }
        $setting = Setting::query()->first();
        if ($setting === null) {
            return false;
        }
        $setting->update(['session' => $session->year]);
        Setting::clearSettingsCache();

        return true;
    }

    /** Get current session year from settings. */
    public function getCurrentSessionYear(): ?string
    {
        $settings = Setting::getCached();

        return $settings['session'] ?? null;
    }
}
