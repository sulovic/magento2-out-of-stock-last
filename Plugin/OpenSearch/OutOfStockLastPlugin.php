<?php

namespace Shoppy\OutOfStockLast\Plugin\OpenSearch;

class QueryBuilderPlugin
{
    public function afterBuild($subject, $result)
    {
        if (!isset($result["body"]["query"])) {
            return $result;
        }

        $oldQuery = $result["body"]["query"];

        $result["body"]["query"] = [
            "function_score" => [
                "query" => $oldQuery,
                "functions" => [
                    [
                        "filter" => [
                            "term" => [
                                "is_salable" => 1,
                            ],
                        ],
                        "weight" => 10,
                    ],
                ],
                "score_mode" => "sum",
                "boost_mode" => "multiply",
            ],
        ];

        return $result;
    }
}
