<?php

namespace App\Imports;

use App\Http\Controllers\AgentController;
use Maatwebsite\Excel\Concerns\ToModel;
use Excel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AgentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $agentController = new AgentController();
        $result = $agentController->logic(agentName: trim(implode(" ", $row)));

        return $result;
    }


    public function headingRow(): int
    {
        return 2;
    }

}
