<?php

namespace Models {
  use Doctrine\Common\Collections\ArrayCollection;
  /**
   * @Entity @Table(name="user")
   **/
  class User
  {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="string") **/
    protected $username;

    /** @Column(type="string") **/
    protected $password;

    /**
     * One User has many posts. This is the inverse side.
     * @OneToMany(targetEntity="Post", mappedBy="user")
     */
    private $posts;

    public function __construct(string $username, string $password)
    {
      $this->username = $username;
      $this->password = $password;
      $this->posts = new ArrayCollection();
    }

    # Accessors
    public function getId(): int
    {
      return $this->id;
    }
    public function getUsername(): string
    {
      return $this->username;
    }
    public function getPassword(): string
    {
      return $this->password;
    }
  }
}
