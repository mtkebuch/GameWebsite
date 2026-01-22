<?php

namespace App\Http\Controllers;

use App\Models\Game;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class GameExportController extends Controller
{
    public function export()
    {
        
        $writer = new Writer();
        $filePath = storage_path('app/games_export.xlsx');
        $writer->openToFile($filePath);
        
     
        $headerRow = Row::fromValues([
            'ID',
            'Title',
            'Category',
            'Price',
            'Created At'
        ]);
        $writer->addRow($headerRow);
        
       
        $games = Game::with('categories')->get();
        
       
        foreach ($games as $game) {
          
            $categories = $game->categories->pluck('name')->join(', ');
            
            $dataRow = Row::fromValues([
                $game->id,
                $game->title,
                $categories,
                $game->price ?? 'N/A',
                $game->created_at->format('Y-m-d H:i')
            ]);
            $writer->addRow($dataRow);
        }
        
        $writer->close();
        
        
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}