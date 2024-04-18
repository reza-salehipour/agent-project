<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Imports\AgentsImport;
use Excel;

class AgentController extends Controller
{
    //send excel file to import file
    /**
     * @param Request $request
     * @return redirect
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'excel' => 'required|file|extensions:csv',
        ]);

        Excel::import(new AgentsImport, $request->file('excel'));

        $request->session()->flash('status', 'Task was successful!');
        return redirect()->back();

    }

    /**
     * @param string $agentName
     * @return Agent::class
     *
     * The main logic is separated here it decide rather the is one owner or more
     */
    function logic(string $agentName)
    {
        if (str_contains($agentName, ' and ') || str_contains($agentName, ' & ')) {
            return $this->agentNameHasTwoParts($agentName);
        } else {
            return $this->agentNameHasOneParts($agentName);
        }
    }

    /**
     * @param $agentName
     * @return Agent|void|null
     *
     * Here homes with  more than one owner are gone through the proper channels
     */
    public function agentNameHasTwoParts($agentName)
    {
        if (str_contains($agentName, ' and ')) {
            $separatedByAndItems = explode(' and ', $agentName);
            $firstNameSeparatedBySpace = explode(' ', $separatedByAndItems[0]);
            $secondNameSeparatedBySpace = explode(' ', $separatedByAndItems[1]);
            $this->extractNames(nameSeparatedBySpace: $firstNameSeparatedBySpace, lastName: end($secondNameSeparatedBySpace));

            return $this->extractNames(nameSeparatedBySpace: $secondNameSeparatedBySpace, lastName: end($secondNameSeparatedBySpace));
        }
        if (str_contains($agentName, ' & ')) {
            $separatedByAndItems = explode(' & ', $agentName);
            $firstNameSeparatedBySpace = explode(' ', $separatedByAndItems[0]);
            $secondNameSeparatedBySpace = explode(' ', $separatedByAndItems[1]);
            $this->extractNames(nameSeparatedBySpace: $firstNameSeparatedBySpace, lastName: end($secondNameSeparatedBySpace));

            return $this->extractNames(nameSeparatedBySpace: $secondNameSeparatedBySpace, lastName: end($secondNameSeparatedBySpace));
        }
    }

    /**
     * @param $agentName
     * @return Agent|null
     */
    public function agentNameHasOneParts($agentName)
    {
        $nameSeparatedBySpace = explode(' ', $agentName);
        return $this->extractNames(nameSeparatedBySpace: $nameSeparatedBySpace);
    }


    /**
     * @param $nameSeparatedBySpace
     * @param $lastName
     * @return Agent|void
     *
     * Here the names are extracted and send to SetName Function to be set
     */
    public function extractNames($nameSeparatedBySpace, $lastName = null)
    {
        switch (count($nameSeparatedBySpace)) {
            case 1:
                return $this->SetNames($nameSeparatedBySpace[0], null, null, $lastName);
            case 2:
                return $this->SetNames($nameSeparatedBySpace[0], null, null, $nameSeparatedBySpace[1]);
            case 3:
                if (str_contains('.', $nameSeparatedBySpace[1]) || strlen($nameSeparatedBySpace[1])==1) {
                    return $this->SetNames($nameSeparatedBySpace[0], null, $nameSeparatedBySpace[1], $nameSeparatedBySpace[2]);
                } else {
                    return $this->SetNames($nameSeparatedBySpace[0], $nameSeparatedBySpace[1], null, $nameSeparatedBySpace[2]);
                }
            case 4:
                return $this->SetNames($nameSeparatedBySpace[0], $nameSeparatedBySpace[1], $nameSeparatedBySpace[2], $nameSeparatedBySpace[3]);
        }
    }

    /**
     * @param $title
     * @param $firstName optional
     * @param $initial optional
     * @param $lastName
     * @return Agent
     *
     * Here the Agent Name is set
     */
    public function SetNames($title, $firstName = null, $initial = null, $lastName): Agent
    {
        $agent = new Agent();

        $agent->title = $title;
        $agent->first_name = $firstName;
        $agent->initial = $initial;
        $agent->last_name = $lastName;

        $agent->save();

        return $agent;
    }
}
