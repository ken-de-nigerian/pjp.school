<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    public $timestamps = false;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'attendance',
        'view_uploaded_attendance',
        'behavioural_analysis',
        'view_uploaded_behavioural_analysis',
        'manage_subjects',
        'upload_result',
        'view_uploaded_results',
        'publish_result',
        'view_published_results',
        'transcript',
        'check_result_status',
        'manage_students',
        'manage_teachers',
        'manage_staffs',
        'online_entrance',
        'manage_scratch_card',
        'news',
        'bulk_sms',
        'general_settings',
    ];

    protected function casts(): array
    {
        $perms = [
            'attendance', 'view_uploaded_attendance', 'behavioural_analysis', 'view_uploaded_behavioural_analysis',
            'manage_subjects', 'upload_result', 'view_uploaded_results', 'publish_result', 'view_published_results',
            'transcript', 'check_result_status', 'manage_students', 'manage_teachers', 'manage_staffs',
            'online_entrance', 'manage_scratch_card', 'news', 'bulk_sms', 'general_settings',
        ];
        $casts = [];
        foreach ($perms as $p) {
            $casts[$p] = 'integer';
        }
        return $casts;
    }

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class, 'user_type', 'id');
    }

    /** Human-readable labels for permissions currently enabled (1) on this role. */
    public function enabledPermissionLabels(): array
    {
        $labels = [];
        foreach (self::permissionKeys() as $label => $column) {
            if ((int) ($this->{$column} ?? 0) === 1) {
                $labels[] = $label;
            }
        }

        return $labels;
    }

    /** Permission keys for forms (label => column). */
    public static function permissionKeys(): array
    {
        $flat = [];
        foreach (self::permissionGroups() as $group) {
            foreach ($group['keys'] as $label => $col) {
                $flat[$label] = $col;
            }
        }

        return $flat;
    }

    /**
     * Permissions grouped for bento-style edit/create UIs.
     *
     * @return list<array{title: string, subtitle: string, icon: string, keys: array<string, string>}>
     */
    public static function permissionGroups(): array
    {
        return [
            [
                'title' => 'Attendance & behaviour',
                'subtitle' => 'Registers attendance and behavioural analysis',
                'icon' => 'fa-clipboard-check',
                'keys' => [
                    'Attendance' => 'attendance',
                    'View Uploaded Attendance' => 'view_uploaded_attendance',
                    'Behavioural Analysis' => 'behavioural_analysis',
                    'View Uploaded Behavioural Analysis' => 'view_uploaded_behavioural_analysis',
                ],
            ],
            [
                'title' => 'Subjects & results',
                'subtitle' => 'Subjects, result uploads, publish, transcripts',
                'icon' => 'fa-graduation-cap',
                'keys' => [
                    'Manage Subjects' => 'manage_subjects',
                    'Upload Result' => 'upload_result',
                    'View Uploaded Results' => 'view_uploaded_results',
                    'Publish Result' => 'publish_result',
                    'View Published Results' => 'view_published_results',
                    'Transcript' => 'transcript',
                    'Check Result Status' => 'check_result_status',
                ],
            ],
            [
                'title' => 'People & accounts',
                'subtitle' => 'Students, teachers, staff',
                'icon' => 'fa-users',
                'keys' => [
                    'Manage Students' => 'manage_students',
                    'Manage Teachers' => 'manage_teachers',
                    'Manage Staffs' => 'manage_staffs',
                ],
            ],
            [
                'title' => 'Portal, comms & settings',
                'subtitle' => 'Entrance, scratch cards, news, SMS, settings',
                'icon' => 'fa-sliders-h',
                'keys' => [
                    'Online Entrance' => 'online_entrance',
                    'Manage Scratch Card' => 'manage_scratch_card',
                    'News' => 'news',
                    'Bulk SMS' => 'bulk_sms',
                    'General Settings' => 'general_settings',
                ],
            ],
        ];
    }
}
