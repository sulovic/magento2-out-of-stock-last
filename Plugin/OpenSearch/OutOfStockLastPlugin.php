<?php

namespace Shoppy\OutOfStockLast\Plugin\OpenSearch;

/**
 * Plugin to modify OpenSearch query to prioritize in-stock products
 */
class OutOfStockLastPlugin
{
    /**
     * After build query, wrap it in function_score to boost in-stock products and modify sort
     *
     * @param \Magento\OpenSearch\SearchAdapter\Query\Builder $subject
     * @param array $result
     * @return array
     */
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
                                "is_out_of_stock" => 0,
                            ],
                        ],
                        "weight" => 5,
                    ],
                ],
                "score_mode" => "sum",
                "boost_mode" => "multiply",
            ],
        ];

        return $result;
    }
}
