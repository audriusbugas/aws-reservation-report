<?php

namespace AWS\Reservations;

class InstanceGroup extends Resource
{

    /**
     * @var array
     */
    private $tags;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @param string $type
     * @param string $availabilityZone
     * @param int $count
     * @param array $tags
     */
    public function __construct(string $type, string $availabilityZone, int $count, array $tags)
    {
        parent::__construct($type, $availabilityZone, $count);
        $this->tags = $tags;
    }

    /**
     * @param array $data
     * @param array $search
     * @return InstanceGroup
     */
    public static function parse(array $data, array $search = [])
    {
        $tags = [];
        $name = null;

        if (isset($data['Tags'])) {
            foreach ($data['Tags'] as $tag) {
                $tags[$tag['Key']] = $tag['Value'];

                foreach ($search as $groupName => $prefixes) {
                    foreach ($prefixes as $prefix) {
                        if (strpos($tag['Value'], $prefix) !== false) {
                            $name = $groupName;
                            break(3);
                        }
                    }
                }

                if ($tag['Key'] == 'Name') {
                    $name = $tag['Value'];
                }
            }
        }

        asort($tags);

        $out = new self(
            $data['InstanceType'],
            $data['Placement']['AvailabilityZone'],
            1,
            $tags
        );

        $name && $out->setName($name);

        return $out;
    }

    /**
     * @return string
     */
    public function getGroupId()
    {
        return $this->getAvailabilityZone() . '/' .
            $this->getType() . '/' .
            $this->getName();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ? $this->name : join(' ', $this->tags);
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
