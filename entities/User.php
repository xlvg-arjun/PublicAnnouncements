<?php

namespace Models {
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

    public function __construct(string $username, string $password)
    {
      $this->username = $username;
      $this->password = $password;
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
