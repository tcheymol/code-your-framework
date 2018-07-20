<?php

namespace WorldCup\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WorldCupController
{
    public function hasWonTheWorldCup(Request $request, string $country): Response
    {
        if (strtolower($country) === 'france') {
            return render_template($request);
        }
        $response = new Response('No.'.rand());

        $response->setTtl(10);

        return $response;
    }
}
