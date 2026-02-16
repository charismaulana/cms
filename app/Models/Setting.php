<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get reminder months before expiration
     */
    public static function getReminderMonths(): int
    {
        return (int) self::get('reminder_months_before', 3);
    }

    /**
     * Get budget warning percentage
     */
    public static function getBudgetWarningPercent(): int
    {
        return (int) self::get('budget_warning_percentage', 20);
    }

    /**
     * Get depleted in warning months threshold
     * Uses the same setting as reminder months
     */
    public static function getDepleteInWarningMonths(): float
    {
        return (float) self::get('reminder_months_before', 6);
    }

    /**
     * Get email subject
     */
    public static function getEmailSubject(): string
    {
        return (string) self::get('email_subject', '[CMS] Contract Expiry & Budget Warning Reminder');
    }

    /**
     * Get email greeting message
     */
    public static function getEmailGreeting(): string
    {
        return (string) self::get('email_greeting', "Dear Team,\n\nThis is an automated reminder from the Contract Management System regarding contracts that require your attention.");
    }

    /**
     * Get email footer message
     */
    public static function getEmailFooter(): string
    {
        return (string) self::get('email_footer', "This is an automated email from the Contract Management System.\nPlease log in to the system for more details.");
    }
}
