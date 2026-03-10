<?php



// ==================== app/Models/Employee.php ====================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'employee_code', 'name', 'position', 'phone',
        'address', 'join_date', 'base_salary', 'salary_type', 'status'
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
            'base_salary' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public static function generateCode(): string
    {
        $last = self::orderByDesc('id')->first();
        $number = $last ? (int) substr($last->employee_code, 3) + 1 : 1;
        return 'EMP' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
