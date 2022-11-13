<?php
namespace morphos\test\Russian;

use morphos\Gender;
use morphos\NamesInflection;
use morphos\Russian\Cases;
use morphos\Russian\FirstNamesInflection;

class FirstNamesInflecionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider allMenNamesProvider
     */
    public function testMutableMen($name)
    {
        $this->assertTrue(FirstNamesInflection::isMutable($name, FirstNamesInflection::MALE));
    }

    /**
     * @dataProvider allWomenNamesProvider
     */
    public function testMutableWomen($name)
    {
        $this->assertTrue(FirstNamesInflection::isMutable($name, FirstNamesInflection::FEMALE));
    }

    public function allMenNamesProvider()
    {
        return [
            ['Август'], ['Авдей'], ['Аверкий'], ['Аверьян'], ['Авксентий'], ['Автоном'], ['Агап'], ['Агафон'], ['Аггей'], ['Адам'], ['Адриан и Андриян'], ['Азарий'], ['Аким'], ['Александр'], ['Алексей'], ['Амвросий'], ['Амос'], ['Ананий'], ['Анатолий'], ['Андрей'], ['Андрон'], ['Андроник'], ['Аникей'], ['Аникита'], ['Анисим и Онисим'], ['Антип'], ['Антонин'], ['Аполлинарий'], ['Аполлон'], ['Арефий'], ['Аристарх'], ['Аркадий'], ['Арсений'], ['Артемий'], ['Артем'], ['Архип'], ['Аскольд'], ['Афанасий'], ['Афиноген'], ['Бажен'], ['Богдан'], ['Болеслав'], ['Борис'], ['Борислав'], ['Боян'], ['Бронислав'], ['Будимир'], ['Вадим'], ['Валентин'], ['Валерий'], ['Валерьян'], ['Варлаам'], ['Варфоломей'], ['Василий'], ['Вацлав'], ['Велимир'], ['Венедикт'], ['Вениамин'], ['Викентий'], ['Виктор'], ['Викторин'], ['Виссарион'], ['Виталий'], ['Владилен'], ['Владлен'], ['Владимир'], ['Владислав'], ['Влас'], ['Всеволод'], ['Всемил'], ['Всеслав'], ['Вышеслав'], ['Вячеслав'], ['Гаврила и Гавриил'], ['Галактион'], ['Гедеон'], ['Геннадий'], ['Георгий'], ['Герасим'], ['Герман'], ['Глеб'], ['Гордей'], ['Гостомысл'], ['Гремислав'], ['Григорий'], ['Гурий'], ['Давыд и Давид'], ['Данила и Даниил'], ['Дементий'], ['Демид'], ['Демьян'], ['Денис'], ['Дмитрий'], ['Добромысл'], ['Доброслав'], ['Дорофей'], ['Евгений'], ['Евграф'], ['Евдоким'], ['Евлампий'], ['Евсей'], ['Евстафий'], ['Евстигней'], ['Егор'], ['Елизар'], ['Елисей'], ['Емельян'], ['Епифан'], ['Еремей'], ['Ермил'], ['Ермолай'], ['Ерофей'], ['Ефим'], ['Ефрем'], ['Захар'], ['Зиновий'], ['Иван'], ['Игнатий'], ['Игорь'], ['Измаил'], ['Изот'], ['Изяслав'], ['Иларион'], ['Илья'], ['Иннокентий'], ['Иосиф'], ['Осип'], ['Ипат'], ['Ипатий'], ['Ипполит'], ['Ираклий'], ['Исай'], ['Исидор'], ['Казимир'], ['Каллистрат'], ['Капитон'], ['Карл'], ['Карп'], ['Касьян'], ['Ким'], ['Кир'], ['Кирилл'], ['Клавдий'], ['Климент'], ['Клементий'], ['Клим'], ['Кондрат'], ['Кондратий'], ['Конон'], ['Константин'], ['Корнил'], ['Корней'], ['Корнилий'], ['Кузьма'], ['Куприян'], ['Лавр'], ['Лаврентий'], ['Ладимир'], ['Ладислав'], ['Лазарь'], ['Лев'], ['Леон'], ['Леонид'], ['Леонтий'], ['Лонгин'], ['Лука'], ['Лукьян'], ['Лучезар'], ['Любим'], ['Любомир'], ['Любосмысл'], ['Макар'], ['Максим'], ['Максимильян'], ['Мариан'], ['Марк'], ['Мартын'], ['Мартьян'], ['Матвей'], ['Мефодий'], ['Мечислав'], ['Милан'], ['Милен'], ['Милий'], ['Милован'], ['Мина'], ['Мир'], ['Мирон'], ['Мирослав'], ['Митофан'], ['Михаил'], ['Михей'], ['Модест'], ['Моисей'], ['Мокей'], ['Мстислав'], ['Назар'], ['Наркис'], ['Натан'], ['Наум'], ['Нестор'], ['Никандр'], ['Никанор'], ['Никита'], ['Никифор'], ['Никодим'], ['Николай'], ['Никон'], ['Нифонт'], ['Олег'], ['Олимпий'], ['Онуфрий'], ['Орест'], ['Остап'], ['Остромир'], ['Павел'], ['Панкратий'], ['Панкрат'], ['Пантелеймон'], ['Панфил'], ['Парамон'], ['Парфен'], ['Пахом'], ['Петр'], ['Пимен'], ['Платон'], ['Поликарп'], ['Порфирий'], ['Потап'], ['Пров'], ['Прокл'], ['Прокофий'], ['Прохор'], ['Радим'], ['Радислав'], ['Радован'], ['Ратибор'], ['Ратмир'], ['Родион'], ['Роман'], ['Ростислав'], ['Рубен'], ['Руслан'], ['Рюрик'], ['Савва'], ['Савватий'], ['Савелий'], ['Самсон'], ['Самуил'], ['Светозар'], ['Святополк'], ['Святослав'], ['Севастьян'], ['Селиван'], ['Селиверст'], ['Семен'], ['Серафим'], ['Сергей'], ['Сигизмунд'], ['Сидор'], ['Силантий'], ['Сильвестр'], ['Симон'], ['Сократ'], ['Соломон'], ['Софон'], ['Софрон'], ['Спартак'], ['Спиридон'], ['Станимир'], ['Станислав'], ['Степан'], ['Стоян'], ['Тарас'], ['Твердислав'], ['Творимир'], ['Терентий'], ['Тимофей'], ['Тимур'], ['Тит'], ['Тихон'], ['Трифон'], ['Трофим'], ['Ульян'], ['Устин'], ['Фадей'], ['Федор'], ['Федосий'], ['Федот'], ['Феликс'], ['Феоктист'], ['Феофан'], ['Ферапонт'], ['Филарет'], ['Филимон'], ['Филипп'], ['Фирс'], ['Флорентин'], ['Фока'], ['Фома'], ['Фортунат'], ['Фотий'], ['Фрол'], ['Харитон'], ['Харлампий'], ['Христофор'], ['Чеслав'], ['Эдуард'], ['Эммануил'], ['Эраст'], ['Эрнест'], ['Эрнст'], ['Ювеналий'], ['Юлиан'], ['Юлий'], ['Юрий'], ['Яков'], ['Ян'], ['Якуб'], ['Януарий'], ['Ярополк'], ['Ярослав']
        ];
    }

    public function allWomenNamesProvider()
    {
        return [
            ['Августа'], ['Агата'], ['Агафья'], ['Агнесса'], ['Агния'], ['Аграфена'], ['Агриппина'], ['Ада'], ['Аделаида'], ['Аза'], ['Алевтина'], ['Александра'], ['Алина'], ['Алиса'], ['Алла'], ['Альбина'], ['Анастасия'], ['Ангелина'], ['Анисья'], ['Анна'], ['Антонида'], ['Антонина'], ['Анфиса'], ['Аполлинария'], ['Ариадна'], ['Беатриса'], ['Берта'], ['Борислава'], ['Бронислава'], ['Валентина'], ['Валерия'], ['Ванда'], ['Варвара'], ['Василиса'], ['Васса'], ['Вера'], ['Вероника'], ['Викторина'], ['Виктория'], ['Виргиния'], ['Влада'], ['Владилена'], ['Владлена'], ['Владислава'], ['Власта'], ['Всеслава'], ['Галина'], ['Галя'], ['Ганна'], ['Генриетта'], ['Глафира'], ['Горислава'], ['Дарья'], ['Диана'], ['Дина'], ['Доминика'], ['Домна'], ['Ева'], ['Евгеиня'], ['Евдокия'], ['Евлампия'], ['Екатерина'], ['Елена'], ['Елизавета'], ['Ефросинья'], ['Жанна'], ['Зинаида'], ['Злата'], ['Изабелла'], ['Изольда'], ['Инга'], ['Инесса'], ['Инна'], ['Ираида'], ['Ирина'], ['Ия'], ['Казимира'], ['Калерия'], ['Капитолина'], ['Каролина'], ['Кира'], ['Клавдия'], ['Клара'], ['Кларисса'], ['Клементина'], ['Клеопатра'], ['Конкордия'], ['Ксения'], ['Лада'], ['Лариса'], ['Леокадия'], ['Лиана'], ['Лидия'], ['Лилиана'], ['Клеопатра'], ['Конкордия'], ['Ксения'], ['Лада'], ['Лариса'], ['Леокадия'], ['Лиана'], ['Лидия'], ['Лилиана'], ['Лилия'], ['Лия'], ['Луиза'], ['Лукерья'], ['Любава'], ['Любомила'], ['Любомира'], ['Людмила'], ['Майя'], ['Мальвина'], ['Маргарита'], ['Марианна'], ['Мариетта'], ['Марина'], ['Мария'], ['Марта'], ['Марфа'], ['Меланья'], ['Мелитриса'], ['Милана'], ['Милена'], ['Милица'], ['Мира'], ['Мирослава'], ['Млада'], ['Мстислава'], ['Муза'], ['Надежда'], ['Наталья'], ['Наталия'], ['Неонила'], ['Ника'], ['Нина'], ['Нона'], ['Оксана'], ['Октябрина'], ['Олимпиада'], ['Ольга'], ['Пелагея'], ['Поликсена'], ['Полина'], ['Прасковья'], ['Пульхерия'], ['Рада'], ['Раиса'], ['Регина'], ['Рената'], ['Римма'], ['Рогнеда'], ['Роза'], ['Розалия'], ['Розина'], ['Ростислава'], ['Руфина'], ['Светлана'], ['Серафима'], ['Сильва'], ['Сильвия'], ['Саломея'], ['Софья'], ['Станислава'], ['Стела'], ['Степанида'], ['Сусанна'], ['Таисия'], ['Тамара'], ['Татьяна'], ['Ульяна'], ['Фаина'], ['Федосья'], ['Фелицата'], ['Флора'], ['Флорентина'], ['Фатина'], ['Харитина'], ['Христина'], ['Эвелина'], ['Элеонора'], ['Эльвира'], ['Эмилия'], ['Эмма'], ['Юлия'], ['Ядвига'], ['Ярослава']
        ];
    }

    /**
     * @dataProvider menNamesProvider()
     */
    public function testInflectionForMen($name, $name2, $name3, $name4, $name5, $name6)
    {
        $this->assertEquals([
            Cases::IMENIT => $name,
            Cases::RODIT => $name2,
            Cases::DAT => $name3,
            Cases::VINIT => $name4,
            Cases::TVORIT => $name5,
            Cases::PREDLOJ => $name6,
        ], FirstNamesInflection::getCases($name, FirstNamesInflection::MALE));
    }

    /**
     * @dataProvider womenNamesProvider()
     */
    public function testInflectionForWomen($name, $name2, $name3, $name4, $name5, $name6)
    {
        $this->assertEquals([
            Cases::IMENIT => $name,
            Cases::RODIT => $name2,
            Cases::DAT => $name3,
            Cases::VINIT => $name4,
            Cases::TVORIT => $name5,
            Cases::PREDLOJ => $name6,
        ], FirstNamesInflection::getCases($name, FirstNamesInflection::FEMALE));
    }

    public function menNamesProvider()
    {
        return [
            ['Иван', 'Ивана', 'Ивану', 'Ивана', 'Иваном', 'Иване'],
            ['Святослав', 'Святослава', 'Святославу', 'Святослава', 'Святославом', 'Святославе'],
            ['Тимур', 'Тимура', 'Тимуру', 'Тимура', 'Тимуром', 'Тимуре'],
            ['Рем', 'Рема', 'Рему', 'Рема', 'Ремом', 'Реме'],
            ['Казбич', 'Казбича', 'Казбичу', 'Казбича', 'Казбичем', 'Казбиче'],
            ['Игорь', 'Игоря', 'Игорю', 'Игоря', 'Игорем', 'Игоре'],
            ['Виль', 'Виля', 'Вилю', 'Виля', 'Вилем', 'Виле'],
            ['Рауль', 'Рауля', 'Раулю', 'Рауля', 'Раулем', 'Рауле'],
            ['Шамиль', 'Шамиля', 'Шамилю', 'Шамиля', 'Шамилем', 'Шамиле'],
            ['Петрусь', 'Петруся', 'Петрусю', 'Петруся', 'Петрусем', 'Петрусе'],
            ['Абай', 'Абая', 'Абаю', 'Абая', 'Абаем', 'Абае'],
            ['Федяй', 'Федяя', 'Федяю', 'Федяя', 'Федяем', 'Федяе'],
            ['Андрей', 'Андрея', 'Андрею', 'Андрея', 'Андреем', 'Андрее'],
            ['Гарей', 'Гарея', 'Гарею', 'Гарея', 'Гареем', 'Гарее'],
            ['Джансуй', 'Джансуя', 'Джансую', 'Джансуя', 'Джансуем', 'Джансуе'],
            ['Ной', 'Ноя', 'Ною', 'Ноя', 'Ноем', 'Ное'],
            ['Дмитрий', 'Дмитрия', 'Дмитрию', 'Дмитрия', 'Дмитрием', 'Дмитрии'],
            ['Гордий', 'Гордия', 'Гордию', 'Гордия', 'Гордием', 'Гордии'],
            ['Пий', 'Пия', 'Пию', 'Пия', 'Пием', 'Пии'],
            ['Геннадий', 'Геннадия', 'Геннадию', 'Геннадия', 'Геннадием', 'Геннадии'],
            ['Хаджибий', 'Хаджибия', 'Хаджибию', 'Хаджибия', 'Хаджибием', 'Хаджибии'],
            ['Никита', 'Никиты', 'Никите', 'Никиту', 'Никитой', 'Никите'],
            ['Данила', 'Данилы', 'Даниле', 'Данилу', 'Данилой', 'Даниле'],
            ['Эйса', 'Эйсы', 'Эйсе', 'Эйсу', 'Эйсой', 'Эйсе'],
            ['Кузьма', 'Кузьмы', 'Кузьме', 'Кузьму', 'Кузьмой', 'Кузьме'],
            ['Мустафа', 'Мустафы', 'Мустафе', 'Мустафу', 'Мустафой', 'Мустафе'],
            ['Байхужа', 'Байхужи', 'Байхуже', 'Байхужу', 'Байхужой', 'Байхуже'],
            // ['Хасанша', 'Хасанши', 'Хасанше', 'Хасаншу', 'Хасаншой', 'Хасанше'],
            ['Карча', 'Карчи', 'Карче', 'Карчу', 'Карчой', 'Карче'],
            ['Гыкга', 'Гыкги', 'Гыкге', 'Гыкгу', 'Гыкгой', 'Гыкге'],
            ['Бетикка', 'Бетикки', 'Бетикке', 'Бетикку', 'Бетиккой', 'Бетикке'],
            ['Анания', 'Анании', 'Анании', 'Ананию', 'Ананией', 'Анании'],
            ['Неемия', 'Неемии', 'Неемии', 'Неемию', 'Неемией', 'Неемии'],
            ['Малахия', 'Малахии', 'Малахии', 'Малахию', 'Малахией', 'Малахии'],
            ['Осия', 'Осии', 'Осии', 'Осию', 'Осией', 'Осии'],
            ['Иеремия', 'Иеремии', 'Иеремии', 'Иеремию', 'Иеремией', 'Иеремии'],
            ['Илия', 'Илии', 'Илии', 'Илию', 'Илией', 'Илии'],
            ['Данило', 'Данилы', 'Даниле', 'Данилу', 'Данилой', 'Даниле'],
            ['Иванко', 'Иванки', 'Иванке', 'Иванку', 'Иванкой', 'Иванке'],
            ['Слава', 'Славы', 'Славе', 'Славу', 'Славой', 'Славе'],
            ['Сергей', 'Сергея', 'Сергею', 'Сергея', 'Сергеем', 'Сергее'],
            ['Илья', 'Ильи', 'Илье', 'Илью', 'Ильей', 'Илье'],
            ['Санек', 'Санька', 'Саньку', 'Санька', 'Саньком', 'Саньке'],
            ['Витёк', 'Витька', 'Витьку', 'Витька', 'Витьком', 'Витьке'],
            ['Салмонбек', 'Салмонбека', 'Салмонбеку', 'Салмонбека', 'Салмонбеком', 'Салмонбеке'],
            ['Саша', 'Саши', 'Саше', 'Сашу', 'Сашей', 'Саше'],
        ];
    }

    public function womenNamesProvider()
    {
        return [
            ['Анна', 'Анны', 'Анне', 'Анну', 'Анной', 'Анне'],
            ['Эра', 'Эры', 'Эре', 'Эру', 'Эрой', 'Эре'],
            ['Асма', 'Асмы', 'Асме', 'Асму', 'Асмой', 'Асме'],
            ['Хафиза', 'Хафизы', 'Хафизе', 'Хафизу', 'Хафизой', 'Хафизе'],
            ['Ольга', 'Ольги', 'Ольге', 'Ольгу', 'Ольгой', 'Ольге'],
            ['Моника', 'Моники', 'Монике', 'Монику', 'Моникой', 'Монике'],
            ['Голиндуха', 'Голиндухи', 'Голиндухе', 'Голиндуху', 'Голиндухой', 'Голиндухе'],
            ['Снежа', 'Снежи', 'Снеже', 'Снежу', 'Снежой', 'Снеже'],
            ['Гайша', 'Гайши', 'Гайше', 'Гайшу', 'Гайшой', 'Гайше'],
            ['Милица', 'Милицы', 'Милице', 'Милицу', 'Милицей', 'Милице'],
            ['Ляуца', 'Ляуцы', 'Ляуце', 'Ляуцу', 'Ляуцей', 'Ляуце'],
            ['Куаца', 'Куацы', 'Куаце', 'Куацу', 'Куацей', 'Куаце'],
            ['Олеся', 'Олеси', 'Олесе', 'Олесю', 'Олесей', 'Олесе'],
            ['Дарья', 'Дарьи', 'Дарье', 'Дарью', 'Дарьей', 'Дарье'],
            ['Майя', 'Майи', 'Майе', 'Майю', 'Майей', 'Майе'],
            ['Моя', 'Мои', 'Мое', 'Мою', 'Моей', 'Мое'],
            ['Пелагея', 'Пелагеи', 'Пелагее', 'Пелагею', 'Пелагеей', 'Пелагее'],
            ['Марция', 'Марции', 'Марции', 'Марцию', 'Марцией', 'Марции'],
            ['Наталия', 'Наталии', 'Наталии', 'Наталию', 'Наталией', 'Наталии'],
            ['Армения', 'Армении', 'Армении', 'Армению', 'Арменией', 'Армении'],
            ['Лия', 'Лии', 'Лии', 'Лию', 'Лией', 'Лии'],
            ['Ия', 'Ии', 'Ии', 'Ию', 'Ией', 'Ии'],
            ['Любовь', 'Любови', 'Любови', 'Любовь', 'Любовью', 'Любови'],
            ['Эсфирь', 'Эсфири', 'Эсфири', 'Эсфирь', 'Эсфирью', 'Эсфири'],
            ['Нинель', 'Нинели', 'Нинели', 'Нинель', 'Нинелью', 'Нинели'],
            ['Айгюль', 'Айгюли', 'Айгюли', 'Айгюль', 'Айгюлью', 'Айгюли'],
            ['Вартануш', 'Вартануши', 'Вартануши', 'Вартануш', 'Вартанушью', 'Вартануши'],
            ['Катиш', 'Катиши', 'Катиши', 'Катиш', 'Катишью', 'Катиши'],
            ['Хуж', 'Хужи', 'Хужи', 'Хуж', 'Хужью', 'Хужи'],
            ['Гуащ', 'Гуащи', 'Гуащи', 'Гуащ', 'Гуащью', 'Гуащи'],
            ['Карач', 'Карачи', 'Карачи', 'Карач', 'Карачью', 'Карачи'],
            ['Мария', 'Марии', 'Марии', 'Марию', 'Марией', 'Марии'],
            ['Дарья', 'Дарьи', 'Дарье', 'Дарью', 'Дарьей', 'Дарье'],
            // ['Манижа', 'Манижы', 'Маниже', 'Манижу', 'Манижей', 'Маниже'],
        ];
    }

    /**
     * @dataProvider immutableNamesProvider()
     */
    public function testImmutableNames($name, $gender)
    {
        $this->assertFalse(FirstNamesInflection::isMutable($name, $gender));
    }

    public function immutableNamesProvider()
    {
        return [
            ['Тореро', FirstNamesInflection::FEMALE],
            ['Айбу', FirstNamesInflection::FEMALE],
            ['Хосе', FirstNamesInflection::FEMALE],
            ['Каншау', FirstNamesInflection::FEMALE],
            ['Франсуа', FirstNamesInflection::FEMALE],
            ['Тойбухаа', FirstNamesInflection::FEMALE],
            ['Качаа', FirstNamesInflection::FEMALE],
            ['Зиа', FirstNamesInflection::FEMALE],
            ['Хожулаа', FirstNamesInflection::FEMALE],
            ['Бетси', FirstNamesInflection::FEMALE],
            ['Элли', FirstNamesInflection::FEMALE],
            ['Энджи', FirstNamesInflection::FEMALE],
            ['Мэри', FirstNamesInflection::FEMALE],
            ['Сью', FirstNamesInflection::FEMALE],
            ['Маро', FirstNamesInflection::FEMALE],
            ['Розмари', FirstNamesInflection::FEMALE],
            ['Алсу', FirstNamesInflection::FEMALE],
            ['Суок', FirstNamesInflection::FEMALE],
            ['Сольвейг', FirstNamesInflection::FEMALE],
            ['Гретхен', FirstNamesInflection::FEMALE],
            ['Ирэн', FirstNamesInflection::FEMALE],
            ['Элен', FirstNamesInflection::FEMALE],
            ['Элис', FirstNamesInflection::FEMALE],
            ['Аннет', FirstNamesInflection::FEMALE],
            ['Джейн', FirstNamesInflection::FEMALE],
            ['Катрин', FirstNamesInflection::FEMALE],
            ['Эстер', FirstNamesInflection::FEMALE],
            ['Акмарал', FirstNamesInflection::FEMALE],
            ['Русудан', FirstNamesInflection::FEMALE],
            ['Шушаник', FirstNamesInflection::FEMALE],
            ['Алтын', FirstNamesInflection::FEMALE],
            ['Гульназ', FirstNamesInflection::FEMALE],

            ['Эрли', FirstNamesInflection::MALE],
            ['Анри', FirstNamesInflection::MALE],
            ['Низами', FirstNamesInflection::MALE],
            ['Оли', FirstNamesInflection::MALE],
            ['Ли', FirstNamesInflection::MALE],
            ['Рево', FirstNamesInflection::MALE],
            ['Ромео', FirstNamesInflection::MALE],
            ['Отто', FirstNamesInflection::MALE],
            ['Педро', FirstNamesInflection::MALE],
            ['Лео', FirstNamesInflection::MALE],
            ['Антонио', FirstNamesInflection::MALE],
            ['Микеле', FirstNamesInflection::MALE],
            ['Андрэ', FirstNamesInflection::MALE],
            ['Хью', FirstNamesInflection::MALE],
            ['Ру', FirstNamesInflection::MALE],
            ['Киану', FirstNamesInflection::MALE],
            ['Грегори', FirstNamesInflection::MALE],
            ['Гиви', FirstNamesInflection::MALE],
            ['Франсуа', FirstNamesInflection::MALE],
        ];
    }

    /**
     * @dataProvider mutableNamesProvider()
     */
    public function testMutableNames($name, $gender)
    {
        $this->assertTrue(FirstNamesInflection::isMutable($name, $gender));
    }

    /**
     * @dataProvider allMenNamesProvider()
     */
    public function testDetectGenderMen($name)
    {
        $result = FirstNamesInflection::detectGender($name);
        if ($result !== null) {
            $this->assertEquals(NamesInflection::MALE, $result);
        }
    }

    /**
     * @dataProvider allWomenNamesProvider()
     */
    public function testDetectGenderWomen($name)
    {
        $result = FirstNamesInflection::detectGender($name);
        if ($result !== null) {
            $this->assertEquals(NamesInflection::FEMALE, $result);
        }
    }

    public function mutableNamesProvider()
    {
        return [
            ['Иван', FirstNamesInflection::MALE],
            ['Игорь', FirstNamesInflection::MALE],
            ['Андрей', FirstNamesInflection::MALE],
            ['Фома', FirstNamesInflection::MALE],
            ['Никита', FirstNamesInflection::MALE],
            ['Илья', FirstNamesInflection::MALE],
            ['Анна', FirstNamesInflection::FEMALE],
            ['Наталья', FirstNamesInflection::FEMALE],
            ['Виринея', FirstNamesInflection::FEMALE],
            // foreign names
            ['Айдын', FirstNamesInflection::MALE],
            ['Наиль', FirstNamesInflection::MALE],
            ['Тукай', FirstNamesInflection::MALE],
            ['Мустафа', FirstNamesInflection::MALE],
            ['Нафиса', FirstNamesInflection::FEMALE],
            ['Асия', FirstNamesInflection::FEMALE],
            ['Лючия', FirstNamesInflection::FEMALE],
        ];
    }

    /**
     * @dataProvider menNamesProvider()
     */
    public function testGetCase($name, $case2)
    {
        $this->assertEquals($case2, FirstNamesInflection::getCase($name, Cases::RODIT, Gender::MALE));
    }
}
