<div class="grid grid-cols-{{ $columns }} gap-2">
    @for ($row = 1; $row <= $rows; $row++)
        @for ($column = 1; $column <= $columns; $column++)
            @php
                $key = $row . '-' . $column;
                $seat = $seats[$key] ?? null;
            @endphp

            <div
                class="border p-2 rounded relative {{ $seat && $seat->student ? 'bg-blue-200 cursor-move' : 'bg-gray-100' }}"
                @if ($seat && $seat->student)
                    draggable="true"
                    ondragstart="dragStart(event, {{ $seat->row }}, {{ $seat->column }})"
                    ondragover="allowDrop(event)"
                    ondrop="drop(event, {{ $row }}, {{ $column }})"
                @endif
                data-row="{{ $row }}"
                data-column="{{ $column }}"
            >
                @if ($seat && $seat->student)
                    {{ $seat->student->name }}
                @else
                    Seat {{ $row }}-{{ $column }}
                @endif
            </div>
        @endfor
    @endfor
</div>

<script>
    let draggedSeat = null;
    let draggedRow = null;
    let draggedColumn = null;

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function dragStart(ev, row, column) {
        draggedSeat = ev.target;
        draggedRow = row;
        draggedColumn = column;
    }

    function drop(ev, row, column) {
        ev.preventDefault();
        if (draggedSeat) {
            @this.updateSeatPosition(draggedRow, draggedColumn, row, column);
            draggedSeat = null;
            draggedRow = null;
            draggedColumn = null;
        }
    }
</script>