<?php

// TODO: set this class in another bundle to remove the bad behaviour on doctrine:schema:update which
// ask for the FOSUserBundle.

// namespace Da\OAuthClientBundle\Entity;

// use Symfony\Component\Security\Core\User\UserInterface;
// use FOS\UserBundle\Model\User as BaseUser;
// use Doctrine\ORM\Mapping as ORM;
// use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
// use Da\OAuthClientBundle\Security\Core\User\OAuthUserInterface;

// /**
//  * User is the user implementation used by the fosub user provider.
//  *
//  * @ORM\Entity
//  * @ORM\Table(name="User")
//  */
// class FosUser extends BaseUser implements OAuthUserInterface
// {
//     /**
//      * @ORM\Id
//      * @ORM\Column(type="integer")
//      * @ORM\GeneratedValue(strategy="AUTO")
//      */
//     protected $id;

//     /**
//      * Construct
//      */
//     public function __construct()
//     {
//         parent::__construct();

//         // Dumb unused password.
//         $this->password = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
//         $this->enabled = true;
//     }

//     /**
//      * {@inheritDoc}
//      */
//     public function setFromResponse(UserResponseInterface $response)
//     {
//         $this->setUsername($response->getNickname());
// 		$this->setEmail($response->getEmail());
//     }

//     /**
//      * {@inheritDoc}
//      */
//     public function getRoles()
//     {
//         return array('ROLE_USER', 'ROLE_OAUTH_USER');
//     }

//     /**
//      * {@inheritDoc}
//      */
//     public function getPassword()
//     {
//         return null;
//     }

//     /**
//      * {@inheritDoc}
//      */
//     public function getSalt()
//     {
//         return null;
//     }

//     /**
//      * {@inheritDoc}
//      */
//     public function eraseCredentials()
//     {
//         return true;
//     }

//     /**
//      * {@inheritDoc}
//      */
//     public function equals(UserInterface $user)
//     {
//         return $user->getUsername() === $this->username;
//     }
// }