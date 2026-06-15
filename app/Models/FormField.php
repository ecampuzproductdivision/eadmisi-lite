<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $fillable = [
        'form_id',
        'field_type',
        'field_name',
        'field_label',
        'placeholder',
        'help_text',
        'options',
        'validation_rules',
        'section',
        'sort_order',
        'is_required',
        'is_active',
        'width',
        'default_value',
    ];

    protected $casts = [
        'options' => 'array',
        'validation_rules' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get available field types for form builder.
     */
    public static function fieldTypes(): array
    {
        return [
            'text'     => ['label' => 'Text (Short)', 'icon' => 'ti-input', 'color' => '#4f46e5'],
            'textarea' => ['label' => 'Textarea (Long)', 'icon' => 'ti-text', 'color' => '#0891b2'],
            'number'   => ['label' => 'Number', 'icon' => 'ti-123', 'color' => '#059669'],
            'email'    => ['label' => 'Email', 'icon' => 'ti-mail', 'color' => '#dc2626'],
            'tel'      => ['label' => 'Phone', 'icon' => 'ti-phone', 'color' => '#d97706'],
            'date'     => ['label' => 'Date', 'icon' => 'ti-calendar', 'color' => '#7c3aed'],
            'select'   => ['label' => 'Dropdown Select', 'icon' => 'ti-select', 'color' => '#0891b2'],
            'radio'    => ['label' => 'Radio Button', 'icon' => 'ti-dot', 'color' => '#db2777'],
            'checkbox' => ['label' => 'Checkbox', 'icon' => 'ti-checkbox', 'color' => '#65a30d'],
            'file'     => ['label' => 'File Upload', 'icon' => 'ti-upload', 'color' => '#ea580c'],
            'color'    => ['label' => 'Color Picker', 'icon' => 'ti-color-picker', 'color' => '#9333ea'],
        ];
    }

    /**
     * Get available column widths.
     */
    public static function widthOptions(): array
    {
        return [
            'col-12'      => 'Full Width (100%)',
            'col-md-6'    => 'Half Width (50%)',
            'col-md-4'    => 'One Third (33%)',
            'col-md-3'    => 'One Quarter (25%)',
            'col-md-8'    => 'Two Thirds (66%)',
        ];
    }
}