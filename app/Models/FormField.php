<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    /**
     * Core/default fields that MUST exist in every form template.
     * These are protected from deletion/editing by admins.
     */
    const CORE_FIELDS = [
        'nama_lengkap' => [
            'field_label' => 'Nama Lengkap',
            'field_type'  => 'text',
            'section'     => 'Data Pribadi',
            'is_required' => true,
            'width'       => 'col-md-6',
            'placeholder' => 'Masukkan nama lengkap',
            'help_text'   => 'Nama lengkap sesuai identitas resmi',
        ],
        'no_hp' => [
            'field_label' => 'Nomor WhatsApp Aktif',
            'field_type'  => 'tel',
            'section'     => 'Data Pribadi',
            'is_required' => true,
            'width'       => 'col-md-6',
            'placeholder' => '08xxxxxxxxxx',
            'help_text'   => 'Nomor WhatsApp yang aktif dan dapat dihubungi',
        ],
        'email' => [
            'field_label' => 'Alamat Email',
            'field_type'  => 'email',
            'section'     => 'Data Pribadi',
            'is_required' => true,
            'width'       => 'col-md-6',
            'placeholder' => 'contoh@email.com',
            'help_text'   => 'Alamat email aktif untuk komunikasi pendaftaran',
        ],
    ];

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
        'is_system',
        'width',
        'default_value',
    ];

    protected $casts = [
        'options' => 'array',
        'validation_rules' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
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

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeNotSystem($query)
    {
        return $query->where('is_system', false);
    }

    /**
     * Check if this field is a core/system field.
     */
    public function isCoreField(): bool
    {
        return $this->is_system || array_key_exists($this->field_name, self::CORE_FIELDS);
    }

    /**
     * Get the list of core field names.
     */
    public static function coreFieldNames(): array
    {
        return array_keys(self::CORE_FIELDS);
    }

    /**
     * Auto-create core fields for a given form if they don't exist.
     * Returns the created fields.
     */
    public static function ensureCoreFields($formId): array
    {
        $created = [];
        $sortOrder = 1;
        foreach (self::CORE_FIELDS as $fieldName => $config) {
            $existing = self::where('form_id', $formId)
                ->where('field_name', $fieldName)
                ->first();
            if (!$existing) {
                $field = self::create([
                    'form_id'     => $formId,
                    'field_type'  => $config['field_type'],
                    'field_name'  => $fieldName,
                    'field_label' => $config['field_label'],
                    'placeholder' => $config['placeholder'],
                    'help_text'   => $config['help_text'],
                    'section'     => $config['section'],
                    'width'       => $config['width'],
                    'is_required' => $config['is_required'],
                    'is_active'   => true,
                    'is_system'   => true,
                    'sort_order'  => $sortOrder++,
                ]);
                $created[] = $field;
            }
            $sortOrder++;
        }
        return $created;
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