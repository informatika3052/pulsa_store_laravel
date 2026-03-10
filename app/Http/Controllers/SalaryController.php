<?php




// ==================== app/Http/Controllers/SalaryController.php ====================
namespace App\Http\Controllers;

use App\Models\{Salary, Employee};
use App\Http\Requests\SalaryRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Salary::with('employee')->latest();

        if ($search = $request->search) {
            $query->whereHas('employee', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        if ($month = $request->month) {
            $query->where('month', $month);
        }
        if ($year = $request->year) {
            $query->where('year', $year);
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $salaries  = $query->paginate(15)->withQueryString();
        $employees = Employee::where('status', 'active')->get();
        return view('salary.index', compact('salaries', 'employees'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        return view('salary.create', compact('employees'));
    }

    public function store(SalaryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['total_salary'] = ($data['base_salary'] ?? 0)
            + ($data['allowance'] ?? 0)
            + ($data['bonus'] ?? 0)
            - ($data['deduction'] ?? 0);

        Salary::create($data);
        return redirect()->route('salary.index')
            ->with('success', 'Data gaji berhasil disimpan!');
    }

    public function printSlip(Salary $salary)
    {
        $salary->load('employee');
        $pdf = Pdf::loadView('salary.slip-pdf', compact('salary'))
            ->setPaper('a5', 'landscape');
        return $pdf->stream("slip-gaji-{$salary->employee->name}-{$salary->month}-{$salary->year}.pdf");
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salary.index')
            ->with('success', 'Data gaji berhasil dihapus!');
    }
}
