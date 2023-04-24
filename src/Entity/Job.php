<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Image;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Company;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $Expires_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
/**
* @ORM\Column(type="string",length=255)
*/
private $image;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->Company;
    }

    public function setCompany(string $Company): self
    {
        $this->Company = $Company;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Descriptioj = $Description;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->Expires_at;
    }

    public function setExpiresAt(\DateTimeImmutable $Expires_at): self
    {
        $this->Expires_at = $Expires_at;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

   /**
* @return mixed
*/
public function getImage()
{
return $this->image;
}
/**
* @param mixed $image
*/
public function setImage($image): void
{
$this->image = $image;
}

}
