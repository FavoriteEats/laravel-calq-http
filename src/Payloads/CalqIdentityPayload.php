<?php
namespace FavoriteEats\CalqHTTP\Payloads;

/**
 * Class CalqIdentityPayload
 * @package FavoriteEats\CalqHTTP
 *
 * Class representing a payload to be sent to the Calq /transfer API endpoint. Includes property setters to help ensure sanity and validation. For further documentation see https://calq.io/docs/client/http.
 */
class CalqIdentityPayload extends CalqPayload {

    /**
     * @var string $oldActor [Required] The old unique Id of the actor (user).
     */
    protected $oldActor;

    /**
     * @var string [Required] The new unique Id to associate actions with. All further actions you send Calq should use this Id instead of the former.
     */
    protected $newActor;

    /**
     * @var array List of payload parameters in order of requirement.
     */
    protected static $params = [
        0 => 'old_actor',
        1 => 'new_actor'
    ];

    /**
     * @var array List of payload parameters required by the API
     */
    protected static $requiredParams = [
        'old_actor',
        'new_actor'
    ];


    /**
     * @return string
     */
    public function getOldActor()
    {
        return $this->oldActor;
    }

    /**
     * @param string $oldActor
     */
    public function setOldActor($oldActor)
    {
        $this->oldActor = $oldActor;
    }

    /**
     * @return string
     */
    public function getNewActor()
    {
        return $this->newActor;
    }

    /**
     * @param string $newActor
     */
    public function setNewActor($newActor)
    {
        $this->newActor = $newActor;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'old_actor' => $this->getOldActor(),
            'new_actor' => $this->getNewActor()
        ];
    }
}
