<div class="d-flex">
    <a href="{{ route('employees.show', ['employee' => $employee->id]) }}" 
       class="btn btn-outline-dark btn-sm me-2 d-flex align-items-center justify-content-center" 
       style="width: 40px; height: 40px;">
        <i class="bi-person-lines-fill fs-4"></i>
    </a>
    <a href="{{ route('employees.edit', ['employee' => $employee->id]) }}" 
       class="btn btn-outline-dark btn-sm me-2 d-flex align-items-center justify-content-center" 
       style="width: 40px; height: 40px;">
        <i class="bi-pencil-square fs-4"></i>
    </a>

    <form action="{{ route('employees.destroy', ['employee' => $employee->id]) }}" method="POST">
        @csrf
        @method('delete')
        <button type="submit" 
                class="btn btn-outline-dark btn-sm d-flex align-items-center justify-content-center" 
                style="width: 40px; height: 40px;">
            <i class="bi-trash fs-4"></i>
        </button>
    </form>
</div>
