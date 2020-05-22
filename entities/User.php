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

    /**
     * Many Users have Many Users.
     * @ManyToMany(targetEntity="User", mappedBy="following")
     */
    private $followers;

    /**
     * Many Users have many Users.
     * @ManyToMany(targetEntity="User", inversedBy="followers")
     * @JoinTable(name="followings",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="followed_user_id", referencedColumnName="id")}
     *      )
     */
    private $following;

    public function __construct(string $username, string $password)
    {
      $this->username = $username;
      $this->password = $password;
      $this->posts = new ArrayCollection();
      $this->followers = new ArrayCollection();
      $this->following = new ArrayCollection();
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
    public function addPost(Post $post): void
    {
      $this->posts->add($post);
    }
    public function follow(User $user)
    {
      $this->following->add($user);
      $this->followers->add($this);
    }
    public function getFollowing()
    {
      return $this->following;
    }
    public function getFollowers()
    {
      return $this->followers;
    }
  }
}
