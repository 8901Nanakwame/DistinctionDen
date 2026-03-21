<x-layouts::app :title="__('Exam Correction')">
    <livewire:exam-correction :exam-id="$exam->id" :attempt-id="$attempt->id" />
</x-layouts::app>
