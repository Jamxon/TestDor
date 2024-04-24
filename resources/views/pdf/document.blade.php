<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bunyodbek Abdurazzaqov</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif
        }

        main {
            margin: 35px 50px;

        }

        .center {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td {
            border: 1px solid black;
            padding: 8px;
            position: relative;
        }

        .table-main {
            font-weight: bold;
        }

        .rule {
            margin-top: 20px;
            font-size: 14px;
            font-weight: bold;
            text-align: left;
        }

        .rule>div {
            margin-top: 10px;
            display: inline-block;
            width: 100%;
        }

        .rule-l {
            float: left;
        }

        .rule-r {
            float: right;
        }

        /* answers first td tag*/
        .answers td:first-child {
            width: 20px;
            text-align: center;
        }

        /* correct::before circle 2px solid black */
        .correct::before {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid black;
            border-radius: 50%;
            margin-right: 10px;
            position: absolute;
            left: 6px;
            top: 6px;

        }

        .question{
            margin-top: 10px;
        }


    </style>
</head>

<body>
<main>
    <div class="center">2023-2024 o`quv yili 1-semestr {{ $data['quizType'] }} nazorati javoblar varaqasi</div>
    <table class="table-main">
        <tr>
            <td>
                Fakultet (yo`nalish)
            </td>
            <td colspan="3">
                {{ $data['studentDepartment'] }}
            </td>
            <td>
                Fan nomi
            </td>
            <td>
                {{ $data['studentSubject'] }}
            </td>
        </tr>
        <tr>
            <td>
                Talaba ID
            </td>
            <td>
                {{ $data['studentId'] }}
            </td>
            <td>
                Guruh
            </td>
            <td>
                {{ $data['studentGuruh'] }}
            </td>
            <td>
                F.I.Sh
            </td>
            <td>
                {{ $data['studentName'] }}
            </td>
    </table>

    <div class="rule">
        * Agar imtixon vaqtida biror shaxsdan yoki manbadan ko’chirayotganim aniqlansa, hech qanday e’tiroz
        bildirmaslikka va ushbu fan bo’yicha nazorat bahoim “0” ga tenglashtirilishiga roziman.
        <div>
            <div class="rule-l">
                Talaba imzosi :………………..
            </div>
            <div class="rule-r">
                Sana: 22.03.2024
            </div>
        </div>
    </div>
    <br>

    <div class="questions">

        <?php
        foreach ($data['studentAnswers'] as $data){
            ?>
        <div class="question">
            <div class="question-text">
                {{ $data->question->question }}
            </div>
        <div class="answer">
            <table class="answers">
                <?php

                $i = 1;
                foreach ($data->question->answers as $answer){
                    ?>

                <tr>
                    <td <?php
                        if($data->answer_id == $answer->id){
                            echo 'class="correct"';
                        }
                            ?>>
                        {{ $i }}
                    </td>
                    <td>
                        {{ $answer->answer }}
                    </td>
                </tr>



                    <?php
                        $i++;
                }?>
            </table>

        </div>

            <?php
        }
        ?>

        </div><!----question end-->





    </div><!----questions end-->

</main>

</body>

</html>
