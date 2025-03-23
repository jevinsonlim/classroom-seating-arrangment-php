<div>
    <p style="text-align: center; margin-bottom: 10px;">Select two seats to swap assigned students.</p>

    <div style="overflow-x: auto; display: flex; justify-content: center;">
        <div style="display: grid; grid-template-rows: repeat({{ $rows }}, auto); grid-template-columns: repeat({{ $columns }}, 120px); gap: 10px; min-width: max-content;">
            @for ($row = 1; $row <= $rows; $row++)
                @for ($column = 1; $column <= $columns; $column++)
                    @php
                        $key = $row . '-' . $column;
                        $seat = $seats[$key] ?? null;
                        $isSelected = in_array($key, $selectedSeats);
                    @endphp

                    <div
                        title="{{ $seat->student ?? '' }}"
                        style="
                            border: 1px solid {{ $isSelected ? 'blue' : 'var(--border-color, #ddd)' }};
                            padding: 20px;
                            border-radius: 6px;
                            position: relative;
                            background-color: {{ $seat && $seat->student ? 'var(--student-seat-bg, #e0f2fe)' : 'var(--seat-bg, #f3f4f6)' }};
                            cursor: pointer;
                            color: var(--text-color, #333);
                            user-select: none;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            min-width: 100px;
                            min-height: 100px;
                            text-align: center;
                            overflow: hidden;
                        "
                        wire:click="selectSeat({{ $row }}, {{ $column }})"
                    >
                        <div class="seat-buttons" style="position: absolute; top: 6px; left: 6px; right: 6px;">
                            <x-filament::icon-button
                                icon="heroicon-o-pencil-square"
                                wire:click.stop="editSeat({{ $row }}, {{ $column }})"
                                style="position: absolute; left: 0;"
                                size="sm"
                                color="info"
                            />

                            <x-filament::icon-button
                                icon="heroicon-o-x-circle"
                                wire:click.stop="clearSeat({{ $row }}, {{ $column }})"
                                style="position: absolute; right: 0;"
                                size="sm"
                                color="danger"
                            />
                        </div>
                        <span style="font-size: {{ strlen($seat->student ?? '') > 7 ? '12px' : '16px' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $seat->student ?? '' }}
                        </span>
                    </div>
                @endfor
            @endfor
        </div>
    </div>

    <x-filament::modal id="edit-seat-modal" width="md">
        <x-slot name="heading">
            Edit Seat
        </x-slot>

        <form wire:submit="updateSeatStudent">
            <x-filament::input wire:model="editingStudent" label="Student Name" />

            <x-slot name="footer">
                <x-filament::button wire:click="updateSeatStudent">
                    Save
                </x-filament::button>
                <x-filament::button color="secondary" x-on:click="$dispatch('close-modal', { id: 'edit-seat-modal' })">
                    Cancel
                </x-filament::button>
            </x-slot>
        </form>
    </x-filament::modal>

    <script>
        document.addEventListener('livewire:load', function () {
            // Dark mode support
            const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

            function updateColors(event) {
                const isDarkMode = event.matches;
                const divs = document.querySelectorAll('[data-row][data-column]');

                divs.forEach(div => {
                    if (isDarkMode) {
                        div.style.setProperty('--student-seat-bg', '#374151');
                        div.style.setProperty('--seat-bg', '#1f2937');
                        div.style.setProperty('--text-color', '#d1d5db');
                        div.style.setProperty('--border-color', '#4b5563');
                    } else {
                        div.style.setProperty('--student-seat-bg', '#e0f2fe');
                        div.style.setProperty('--seat-bg', '#f3f4f6');
                        div.style.setProperty('--text-color', '#333');
                        div.style.setProperty('--border-color', '#ddd');
                    }
                });
            }

            updateColors(darkModeMediaQuery);
            darkModeMediaQuery.addEventListener('change', updateColors);
        });
    </script>
</div>