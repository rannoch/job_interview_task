<?php
// PHP 7.2
// Charset: Windows-1251

/**
 * Класс-стенд с академическими задачами #2.
 * Небходимо заполнить все пропуски в классе так, чтобы он
 * удовлетворял поставленным assert'ам
 */
class JobSeeker
{
    /**
     * Assert #1
     */
    public static function questClassSearch(): bool
    {
        return spl_autoload_register(function (string $c): void {
            class_alias(self::class, $c);
        });
    }

    /**
     * Assert #2
     */
    public function questString(string $str): bool
    {
        $name1 = 'call_user_func';
        $name2 = 'md5';
        return $name1 <> $name2
            && $name1($name2, $str) === $name2($str)
            && strlen($name2($str, true)) === 020;
    }

    /**
     * Assert #3
     * Пожалуйста, введите достоверную информацию о себе.
     */
    public function questGreeting(string $format)
    {
        $greeting = sprintf(
            $format,
            ...explode(',', '29,г. Якутска,Василий')
        );
        settype($greeting, 'object');
        return $greeting;
    }

    /**
     * Assert #4
     */
    public function questReducer(string $str): string
    {
        return str_replace(str_split('abcbbb', 2), '', $str);
    }

    /**
     * Assert #5
     */
    public function questKey(int $key, int $i, int $j): bool
    {
        $i = ($i & 15) << 4;
        $j = ($j >> 5) ^ 0;
        return ($i | $j) === $key;
    }

    /**
     * Assert #6
     */
    public function questRegExp(string $str): string
    {
        $str = preg_replace(
            "/[^[0-9]*]*([1-9][0-9]+?).*?(ms|mr);'(.+?)(?=';|'$).*/",
            '$2. $3 has $1 coins',
            $str
        );
        return stripslashes($str);
    }

    /**
     * Assert #7
     */
    public function __get($arg)
    {
        return new class((is_callable($this) ? $this() : 0) + $arg) extends JobSeeker
        {

            protected $arg = 0;

            public function __invoke()
            {
                return $this->arg;
            }

            public function __construct($arg)
            {
                $this->arg = $arg;
            }
        };
    }

    /**
     * Assert #8
     */
    public function questLabyrinth(int $length, ...$map): bool
    {
        $path = '1001101111222111001101';
        $linesCount = count($map);
        foreach ($map as &$line) {
            $line = str_split(str_pad(decbin($line), $length, '0', STR_PAD_LEFT));
            foreach ($line as &$cell) {
                $cell = (int)$cell + 1;
            }
        }

        $x = 0;
        $y = 0;
        $i = 0;
        while (
            $y >= 0
            && $y < $linesCount
            && $x < $length
            && $map[$y][$x] === 1
            && $i < strlen($path)
        ) {
            $moveY = $map[$y][$x] <=> $path[$i++];
            $x += $moveY === 0 ? 1 : 0;
            $y += $moveY;
        }
        return $x === $length - 1 && $y === $linesCount - 1;
    }
}

$s = new JobSeeker();
assert(
    !class_exists('Foo')
    && !class_exists('Bar\Foo')
    && true === JobSeeker::questClassSearch()
    && class_exists('Foo')
    && class_exists('Bar\Foo')
);

assert(
    (new JobSeeker())->questString('Hello world!')
);

assert(
    (new JobSeeker())->questReducer('babbacbbcabbccabb') === 'abc'
);

$s = new JobSeeker();
assert(
    $s->questKey(202, 92, 331)
    && $s->questKey(115, 39, 97)
    && $s->questKey(15, 268435456, 480)
);

// Assert 6/8
$s = new JobSeeker();
assert(
    $s->questRegExp("15.2;mr;'D\\'Artagnan';'Moscow'") === 'mr. D\'Artagnan has 15 coins'
    && $s->questRegExp("female;43.0;'Minsk';ms;'Ekaterina'") === 'ms. Ekaterina has 43 coins'
    && $s->questRegExp("54;'Tokyo';mr;'Suihui\\'v Chai'") === 'mr. Suihui\'v Chai has 54 coins'
);

// Assert 7/8
$s = new JobSeeker();
assert(
    ($s->{4}->{5}->{3})() === 12,
    ($s->{2}->{2})() === 4
);

//Assert 8/8
assert(
    (new JobSeeker())->questLabyrinth(14, 2309, 11316, 9121, 10276)
);