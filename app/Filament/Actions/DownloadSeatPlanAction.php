<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\HTML as HTMLReader;
use PhpOffice\PhpWord\Writer\Word2007;
use Illuminate\Support\Facades\View;

class DownloadSeatPlanAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'downloadSeatPlanWord';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Download Seat Plan')
            ->icon('heroicon-m-arrow-down-on-square-stack')
            ->action(function ($record) {
                $sectionName = $record->section->name;
                $subjectName = $record->subject;
                $seats = $record->seats->sortBy(['row', 'column']);

                $maxRow = $record->rows;
                $maxColumn = $record->columns;

                $html = View::make('seatplan', [
                    'sectionName' => $sectionName,
                    'subjectName' => $subjectName,
                    'seats' => $seats,
                    'maxRow' => $maxRow,
                    'maxColumn' => $maxColumn,
                ])->render();

                $tempHtmlFile = tempnam(sys_get_temp_dir(), 'seat_plan_html_');
                file_put_contents($tempHtmlFile, $html);

                $phpWord = new PhpWord();
                $htmlReader = new HTMLReader($phpWord);
                $phpWord = $htmlReader->load($tempHtmlFile);

                unlink($tempHtmlFile);

                $writer = new Word2007($phpWord);
                $tempDocxFile = tempnam(sys_get_temp_dir(), 'seat_plan_docx_');
                $writer->save($tempDocxFile);

                return response()->download($tempDocxFile, $sectionName . '-' . $subjectName . '-seat-plan-grid.docx')->deleteFileAfterSend(true);
            });
    }
}