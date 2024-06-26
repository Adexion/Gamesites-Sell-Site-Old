<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractRemoteEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $ip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $port;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255, name="db")
     */
    protected $database;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $directory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $displayName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $columnOne;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected $additionalFields;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=true)
     */
    protected $databaseType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $orderBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getPort(): ?string
    {
        return $this->port;
    }

    public function setPort(?string $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDatabase(): ?string
    {
        return $this->database;
    }

    public function setDatabase(string $database): self
    {
        $this->database = $database;

        return $this;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getColumnOne(): ?string
    {
        return $this->columnOne;
    }

    public function setColumnOne(?string $columnOne): self
    {
        $this->columnOne = $columnOne;

        return $this;
    }

    public function getDatabaseType(): ?int
    {
        return $this->databaseType;
    }

    public function setDatabaseType(?int $databaseType): self
    {
        $this->databaseType = $databaseType;

        return $this;
    }

    abstract function getSearchFields(): array;

    public function toArray(): array
    {
        return [$this->login, $this->password, $this->ip, $this->port, $this->database];
    }

    public function getAdditionalFields(): ?array
    {
        return $this->additionalFields;
    }

    public function setAdditionalFields(?array $additionalFields): self
    {
        $this->additionalFields = $additionalFields;

        return $this;
    }

    public function getAdditionalFieldIconByNameList(): array
    {
        foreach ($this->additionalFields as $field) {
            $names[$field['name']] = $field['icon'];
        }

        return $names ?? [];
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy ?: $this->columnOne;
    }

    public function setOrderBy(?string $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName($displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }
}