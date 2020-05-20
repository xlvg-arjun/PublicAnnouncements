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
    protected $name;

    /** @Column(type="string") **/
    protected $ip;

    public function __construct(string $name, string $ip)
    {
      $this->name = $name;
      $this->ip = $ip;
    }

    # Accessors
    public function getId(): int
    {
      return $this->id;
    }
    public function getName(): string
    {
      return $this->name;
    }
    public function getIp(): string
    {
      return $this->ip;
    }
  }
}
