<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipment
 *
 * @ORM\Table(name="equipment")
 * @ORM\Entity
 */
class Equipment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="item", type="string", length=255)
     */
    private $item;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Unit")
     */
    private $unit;

    /**
     * @var integer
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;

    /** @ORM\ManyToOne(targetEntity="Point", inversedBy="equipment") */
    private $point;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set item
     *
     * @param string $item
     * @return Equipment
     */
    public function setItem($item)
    {
        $this->item = $item;
    
        return $this;
    }

    /**
     * Get item
     *
     * @return string 
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set total
     *
     * @param integer $total
     * @return Equipment
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return integer 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set unit
     *
     * @param \Geekhub\DreamBundle\Entity\Unit $unit
     * @return Equipment
     */
    public function setUnit(\Geekhub\DreamBundle\Entity\Unit $unit = null)
    {
        $this->unit = $unit;
    
        return $this;
    }

    /**
     * Get unit
     *
     * @return \Geekhub\DreamBundle\Entity\Unit 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set point
     *
     * @param \Geekhub\DreamBundle\Entity\Point $point
     * @return Equipment
     */
    public function setPoint(\Geekhub\DreamBundle\Entity\Point $point = null)
    {
        $this->point = $point;
    
        return $this;
    }

    /**
     * Get point
     *
     * @return \Geekhub\DreamBundle\Entity\Point 
     */
    public function getPoint()
    {
        return $this->point;
    }
}