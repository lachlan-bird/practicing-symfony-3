<?php
/**
 * Created by PhpStorm.
 * User: lachlan
 * Date: 15/8/18
 * Time: 7:35 PM
 */

namespace AppBundle\Doctrine;

use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class HashPasswordListener implements EventSubscriber
{
    private $encoder;

    public function __construct(UserPasswordEncoder $encoder)
    {

        $this->encoder = $encoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);

        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    /**
     * @param $entity
     */
    private function encodePassword(User $entity): void
    {
        $encoded = $this->encoder->encodePassword($entity, $entity->getPlainPassword());
        $entity->setPassword($encoded);
    }
}