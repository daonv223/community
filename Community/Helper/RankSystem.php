<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Math\Random;
use Magento\Framework\Serialize\SerializerInterface;

class RankSystem extends AbstractHelper
{
    private SerializerInterface $serializer;

    private Random $mathRandom;

    public function __construct(Context $context, SerializerInterface $serializer, Random $mathRandom)
    {
        parent::__construct($context);
        $this->serializer = $serializer;
        $this->mathRandom = $mathRandom;
    }

    public function makeStorableArrayFieldValue($value)
    {
        $newValue = [];
        unset($value['__empty']);
        foreach ($value as $row) {
            if (!is_array($row)
                || !array_key_exists('label', $row)
                || !array_key_exists('point', $row)
            ) {
                continue;
            }
            $newValue[$row['point']] = $row['label'];
        }
        return $this->serializer->serialize($newValue);
    }

    public function makeArrayFieldValue($value)
    {
        $result = [];
        $value = $this->serializer->unserialize($value);
        foreach ($value as $point => $label) {
            $resultId = $this->mathRandom->getUniqueHash('_');
            $result[$resultId] = ['label' => $label, 'point' => $point];
        }
        return $result;
    }

    public function getRank(int $point): string
    {
        $rankConfig = $this->scopeConfig->getValue('community_rank_system/configuration/ranks');
        $rankConfig = $this->serializer->unserialize($rankConfig);
        ksort($rankConfig);
        $rank = 'Newcomer';
        foreach ($rankConfig as $key => $value) {
            if ($point > $key) {
                $rank = $value;
            } else {
                break;
            }
        }
        return $rank;
    }
}
