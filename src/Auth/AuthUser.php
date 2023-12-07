<?php declare(strict_types=1);

namespace Hyvor\Helper\Auth;

use Hyvor\Helper\Auth\Providers\CurrentProvider;
use Illuminate\Support\Collection;

/**
 * @phpstan-type AuthUserArray array{
 *  id: int,
 *  username: string,
 *  name: string,
 *  email: string,
 *  picture_url?: string,
 *  location?: string,
 *  bio?: string,
 *  website_url?: string,
 *  sub?: string,
 * }
 *
 * @phpstan-type AuthUserArrayPartial array{
 * id?: int,
 * username?: string,
 * name?: string,
 * email?: string,
 * picture_url?: string,
 * location?: string,
 * bio?: string,
 * website_url?: string,
 * sub?: string,
 * }
 */
class AuthUser
{

    public function __construct(
        public int $id,
        public string $username,
        public string $name,
        public string $email,
        public ?string $picture_url = null,
        public ?string $location = null,
        public ?string $bio = null,
        public ?string $website_url = null,
        // only for OIDC
        public ?string $sub = null,
    )
    {}

    /**
     * @param AuthUserArray $data
     */
    public static function fromArray(array $data) : self
    {
        return new self(
            id: $data['id'],
            username: $data['username'],
            name: $data['name'],
            email: $data['email'],
            picture_url: $data['picture_url'] ?? null,
            location: $data['location'] ?? null,
            bio: $data['bio'] ?? null,
            website_url: $data['website_url'] ?? null,
            sub: $data['sub'] ?? null,
        );
    }

    /**
     * @param iterable<int> $ids
     * @return Collection<int, self>
     */
    public static function fromIds(iterable $ids)
    {
        return CurrentProvider::getImplementation()->fromIds($ids);
    }

    public static function fromId(int $id) : ?self
    {
        return CurrentProvider::getImplementation()->fromId($id);
    }

    /**
     * @param iterable<string> $usernames
     * @return Collection<string, AuthUser>
     */
    public static function fromUsernames(iterable $usernames)
    {
        return CurrentProvider::getImplementation()->fromUsernames($usernames);
    }

    public static function fromUsername(string $username) : ?self
    {
          return CurrentProvider::getImplementation()->fromUsername($username);
    }

    /**
     * @param iterable<string> $emails
     * @return Collection<string, AuthUser>
     */
    public static function fromEmails(iterable $emails)
    {
        return CurrentProvider::getImplementation()->fromEmails($emails);
    }

    public static function fromEmail(string $email) : ?self
    {
          return CurrentProvider::getImplementation()->fromEmail($email);
    }

}