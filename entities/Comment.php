<?php

namespace Models {
  /**
   * @Entity @Table(name="comment")
   **/
  class Comment
  {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="string") **/
    protected $content;

    /**
     * Many comments have one post
     * @ManyToOne(targetEntity="Post")
     * @JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * Many comments have one user
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct(string $content)
    {
      $this->content = $content;
    }

    public function getContent(): string
    {
      return $this->content;
    }

    public function getPost(): \Models\Post
    {
      return $this->post;
    }

    public function getUser(): \Models\User
    {
      return $this->user;
    }
  }
}
