<?php

namespace artkost\qa\actions;

use artkost\qa\models\Tag;
use yii\web\Response;

class TagSuggestAction extends Action
{

    /**
     * @return Response
     */
    public function run()
    {
        $response = [
            'data' => ['status' => false],
            'format' => 'json'
        ];

        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $response['data']['items'] = $this->getModel()->suggest($keyword);
        }

        return new Response($response);
    }

}
