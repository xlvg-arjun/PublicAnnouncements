<?php

namespace Models {
  use Doctrine\Common\Collections\ArrayCollection;
  /**
   * @Entity @Table(name="post")
   **/
  class Post
  {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="string") **/
    protected $title;

    /** @Column(type="string") **/
    protected $mainText;

    /**
     * Many Posts have one user
     * @ManyToOne(targetEntity="User", inversedBy="post")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * One Post has many comments. This is the inverse side.
     * @OneToMany(targetEntity="Comment", mappedBy="post")
     */
    private $comments;

    public function __construct(string $title, string $mainText)
    {
      $this->title = $title;
      $this->mainText = $mainText;
      $this->comments = new ArrayCollection();
    }

    # Accessors
    public function getId(): int
    {
      return $this->id;
    }
    public function getTitle(): string
    {
      return $this->title;
    }
    public function getMainText(): string
    {
      return $this->mainText;
    }
    public function setUser($user): void
    {
      $this->user = $user;
    }
    public function getUser(): User
    {
      return $this->user;
    }
    public function getComments(): ArrayCollection
    {
      return $this->comments;
    }
  }
}
