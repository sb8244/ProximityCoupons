<?php
namespace Application\Model;
use Application\Model\Document\User;
use Application\Model\Document\Coupon;
class CouponFinder
{
    private $id;
    
    public function __construct(User $user)
    {
        $this->id = $user->getId();
    }
    
    public function checkDeal($id, $lat, $lng)
    {
        $deals = $this->locateDeals($lat, $lng);
        foreach($deals as $deal) {
            
            if($deal['coupon']->getId() == $id 
                && $deal['valid'] === true)
            {
                if($deal['coupon']->claimed_by == null 
                    || !in_array($this->id, $deal['coupon']->claimed_by->export()))
                {
                        return true;
                }
                else
                {
                    return 'Already claimed';
                }
            }
        }
        return 'Deal does not apply';
    }
    
    public function locateDeals($lat, $lng)
    {
        $lat = floatval($lat);
        $lng = floatval($lng);
        
        //Have to manually use Mongo because I need to
        //execute the geoNear db command
        
        $mongo = new \Mongo();
        $res = $mongo->yhack->command(array(
                "geoNear" => "Coupon",
                "near" => array($lng, $lat),
                "spherical" => true
        ));
        
        $deals = array();
        foreach($res['results'] as $item)
        {
            $valid = false;
            //the distance calculated is less than the proximity
            if($item['dis'] <= $item['obj']['proximity'])
            {
                $valid = true;
            }
            $id = $item['obj']['_id'];
            $coupon = Coupon::find($id);
            $deals[] = array(
                'meters' => $item['dis'],
                'coupon' => $coupon,
                'valid' => $valid
            );
        }
        return $deals;
    }
    
}

