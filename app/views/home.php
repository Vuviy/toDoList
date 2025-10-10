<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <title>To‑Do List</title>
    <style>
        :root {
            --bg: #0f1724;
            --card: #0b1220;
            --accent: #6ee7b7;
            --muted: #9aa4b2;
            --danger: #ff6b6b;
            --warning: #ffb86b;
            --glass: rgba(255, 255, 255, 0.03);
            --radius: 14px;
            --glass-2: rgba(255, 255, 255, 0.02);
        }

        * {
            box-sizing: border-box;
            font-family: Inter, Segoe UI, Roboto, Arial, sans-serif
        }

        html, body {
            height: 100%;
            margin: 0;
            background: linear-gradient(180deg, #061021 0%, #07122a 100%);
            color: #e6eef6
        }

        .wrap {
            min-height: 100%;
            display: grid;
            place-items: center;
            padding: 32px
        }

        .card {
            width: 100%;
            max-width: 900px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.1), transparent);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.6);
            backdrop-filter: blur(6px);
        }

        h1 {
            margin: 0 0 12px;
            font-size: 20px;
            display: flex;
            gap: 12px;
            align-items: center
        }

        .subtitle {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 18px
        }

        form {
            display: grid;
            grid-template-columns:repeat(12, 1fr);
            gap: 10px;
            align-items: end
        }

        label {
            font-size: 13px;
            color: var(--muted)
        }

        input[type="text"], select, input[type="date"] {
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid var(--glass);
            background: transparent;
            color: inherit;
            outline: none
        }

        .col-6 {
            grid-column: span 6
        }

        .col-4 {
            grid-column: span 4
        }

        .col-3 {
            grid-column: span 3
        }

        .col-12 {
            grid-column: span 12
        }

        button {
            padding: 10px 14px;
            border-radius: 10px;
            border: 0;
            background: var(--accent);
            color: #032;
            cursor: pointer;
            font-weight: 600
        }

        .tasks {
            margin-top: 20px
        }

        .task {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 12px;
            border-radius: 12px;
            background: var(--glass-2);
            border: 1px solid rgba(255, 255, 255, 0.02)
        }

        .task + .task {
            margin-top: 10px
        }

        .task .info {
            flex: 1
        }

        .task .title {
            font-weight: 700
        }

        .task .meta {
            font-size: 13px;
            color: var(--muted);
            margin-top: 6px
        }

        .badge {
            padding: 6px 8px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 12px
        }

        .cat-1 {
            background: rgba(102, 126, 234, 0.12);
            color: #9fb0ff
        }

        .cat-2 {
            background: rgba(110, 231, 183, 0.08);
            color: var(--accent)
        }

        .cat-3 {
            background: rgba(255, 182, 107, 0.06);
            color: var(--warning)
        }

        .prio-low {
            background: rgba(255, 255, 255, 0.03);
            color: var(--muted)
        }

        .prio-med {
            background: rgba(255, 255, 255, 0.04);
            color: var(--warning)
        }

        .prio-high {
            background: rgba(255, 0, 80, 0.08);
            color: var(--danger)
        }

        .controls {
            display: flex;
            gap: 8px
        }

        .icon-btn {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px;
            border-radius: 10px;
            cursor: pointer
        }

        .center-small {
            display: flex;
            gap: 8px;
            align-items: center
        }

        option {
            background: #0b1220;
            color: #e6eef6;
        }

        .empty {
            color: var(--muted);
            padding: 18px;
            text-align: center
        }

        @media (max-width: 720px) {
            form {
                grid-template-columns:repeat(6, 1fr)
            }

            .col-6 {
                grid-column: span 6
            }

            .col-4 {
                grid-column: span 3
            }

            .col-3 {
                grid-column: span 3
            }
        }

        .filter_sort {
            margin-top: 25px;
            background: #f3f4f6;
            padding: 18px 20px;
            border-radius: 12px;
            border: 1px solid #ddd;
        }

        .filter_sort h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }

        .filters-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }

        .filters-row .col-2,
        .filters-row .col-3,
        .filters-row .col-4 {
            flex: 1;
            min-width: 200px;
        }

        .filter_sort label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #444;
            font-weight: 600;
        }

        .filter_sort input[type="text"],
        .filter_sort select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            background-color: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .filter_sort input[type="text"]:focus,
        .filter_sort select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            outline: none;
        }

        @media (max-width: 768px) {
            .filters-row {
                flex-direction: column;
            }
        }

        .filter_sort input[type="text"],
        .filter_sort select {
            color: #111;
            background-color: #fff;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 25px;
            gap: 8px;
        }

        .page-link {
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #fff;
            text-decoration: none;
            color: #333;
            transition: background 0.2s, color 0.2s;
        }

        .page-link:hover {
            background: #007bff;
            color: white;
        }

        .page-link.active {
            background: #007bff;
            color: white;
            font-weight: bold;
            pointer-events: none;
        }


    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>📝 Мій To‑Do список</h1>
        <div class="subtitle">Додай завдання з назвою, категорією, пріоритетом та терміном виконання. Все зберігається
            локально.
        </div>

        <form id="taskForm" autocomplete="off">
            <div class="col-6">
                <label for="title">Назва</label>
                <input id="title" name="title" type="text" placeholder="Наприклад: Зробити код-рев'ю" required>
            </div>

            <div class="col-3">
                <label for="category">Категорія</label>
                <select id="category" name="category">
                    <option value="1">Робота</option>
                    <option value="2">Особисте</option>
                    <option value="3">Навчання</option>
                </select>
            </div>

            <div class="col-3">
                <label for="priority">Пріоритет</label>
                <select id="priority" name="priority">
                    <option value="1">Низький</option>
                    <option value="2" selected>Середній</option>
                    <option value="3">Високий</option>
                </select>
            </div>

            <div class="col-4">
                <label for="date">Термін виконання</label>
                <input name="date" id="date" type="date">
                <!--                <input name="id" type="text" value="68e284bb65347">-->
            </div>

            <div class="col-4">
                <label>&nbsp;</label>
                <button id="addBtn" type="submit">Додати завдання</button>
            </div>

            <!--            <div class="col-4 center-small">-->
            <!--                <label>&nbsp;</label>-->
            <!--                <div style="display:flex;gap:8px">-->
            <!--                    <button id="clearCompleted" type="button" class="icon-btn" title="Прибрати виконані">Очистити виконані</button>-->
            <!--                    <button id="clearAll" type="button" class="icon-btn" title="Видалити всі">Видалити всі</button>-->
            <!--                </div>-->
            <!--            </div>-->
        </form>
        <div class="filter_sort">
            <h3>🔍 Фільтри та сортування</h3>

            <div class="filters-row">
                <form id="filterForm" class="filter_sort" method="GET" action="">

                    <div class="col-4">
                        <label for="search">Пошук за назвою</label>
                        <input type="text" id="search" name="search" placeholder="Введіть текст для пошуку...">
                    </div>

                    <div class="col-3">
                        <label for="filter_category">Категорія</label>
                        <select id="filter_category" name="filter_category">
                            <option value="">Усі категорії</option>
                            <option value="1">Робота</option>
                            <option value="2">Особисте</option>
                            <option value="3">Навчання</option>
                        </select>
                    </div>

                    <div class="col-3">
                        <label for="filter_priority">Пріоритет</label>
                        <select id="filter_priority" name="filter_priority">
                            <option value="">Усі пріоритети</option>
                            <option value="1">Низький</option>
                            <option value="2">Середній</option>
                            <option value="3">Високий</option>
                        </select>
                    </div>

                    <div class="col-2">
                        <label for="sort">Сортувати за</label>
                        <select id="sort" name="sort">
                            <option value="" selected>без сортування</option>
                            <option value="date_desc">Термін ↓</option>
                            <option value="date_asc">Термін ↑</option>
                            <option value="priority_desc">Пріоритет ↓</option>
                            <option value="priority_asc">Пріоритет ↑</option>
                            <option value="title_asc">Назва A-Z</option>
                            <option value="title_desc">Назва Z-A</option>
                        </select>
                    </div>
                    <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="submit" class="btn-primary">Застосувати</button>
                        <button type="reset" class="btn-secondary">Скинути</button>
                    </div>

                </form>
            </div>
        </div>

        <div id="error"></div>

        <div class="tasks" id="tasksList">

            <?php
            if ($result['total'] > 0) {
                foreach ($result['data'] as $task) {
                    $categories = [
                        1 => 'Робота',
                        2 => 'Особисте',
                        3 => 'Навчання',
                    ];

                    $catName = $categories[$task->getFields()['category']] ?? 'Невідомо';

                    $priorities = [
                        1 => 'Низький',
                        2 => 'Середній',
                        3 => 'Терміново',
                    ];

                    $prioritiesClass = [
                        1 => 'low',
                        2 => 'medium',
                        3 => 'high',
                    ];

                    $priorityName = $priorities[$task->getFields()['priority']] ?? 'Невідомо';
                    $priorityClass = $prioritiesClass[$task->getFields()['priority']] ?? 'high';


                    ?>
                    <div class="task" data-id="<?php echo htmlspecialchars($task->getFields()['id']) ?>">
                        <div class="info">
                            <div class="title"><?php echo htmlspecialchars($task->getFields()['title']) ?></div>
                            <div class="meta">
                                <span class="badge cat-<?php echo htmlspecialchars($task->getFields()['category']) ?>"><?php echo htmlspecialchars($catName) ?></span>
                                &nbsp;
                                <span class="badge prio-<?php echo htmlspecialchars($priorityClass) ?>"><?php echo htmlspecialchars($priorityName) ?></span>
                                &nbsp; • &nbsp;
                                <strong>Термін:</strong> <span class="date-task"><?php echo htmlspecialchars($task->getFields()['date']) ?></span>
                            </div>
                        </div>
                        <div class="controls">
                            <button class="icon-btn">Готово</button>
                            <button class="icon-btn update"
                                    data-task-id="<?php echo htmlspecialchars($task->getFields()['id']) ?>">Редаг.
                            </button>
                            <button class="icon-btn delete"
                                    data-task-id="<?php echo htmlspecialchars($task->getFields()['id']) ?>">Видал.
                            </button>
                        </div>
                    </div>


                    <?php
                }
            } else {
                ?>
                <div class="empty">Список завдань порожній — додай перше завдання вище.</div>
                <?php
            }
            ?>
        </div>

        <?php if ($result['pages'] > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $result['pages']; $i++): ?>
                    <?php
                    $query = $_GET;
                    $query['page'] = $i;
                    $queryString = http_build_query($query);
                    ?>
                    <a href="?<?= htmlspecialchars($queryString) ?>"
                       class="page-link <?= $i == $result['page'] ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>

    $(function () {
        $('#taskForm').on('submit', function (e) {
            e.preventDefault();

            let update = $(this).serialize().includes('id=');
            let action = '/save';
            if (update) {
                action = '/update';
            }
            $.ajax({
                url: action,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (resp) {
                    location.reload();
                },
                error: function (xhr) {
                    showError(xhr);
                }
            });
        });
    });

    $(function () {
        $('.delete').on('click', function () {
            let id = $(this).data('task-id');
            $.ajax({
                url: '/delete',
                type: 'POST',
                data: {
                    'id': id,
                },
                dataType: 'json',
                success: function (resp) {
                    $(`.task[data-id="${id}"]`).remove();
                },
                error: function (xhr) {
                    showError(xhr);
                }
            });
        });
    });

    $(function () {
        $('.update').on('click', function () {
            let id = $(this).data('task-id');
            let task = $(`.task[data-id="${id}"]`);


            let title = task.find('.title').text().trim();
            let category = task.find('.badge[class*="cat-"]').attr('class').match(/cat-(\d+)/)[1];
            let priority = task.find('.badge[class*="prio-"]').attr('class').includes('low') ? 1 :
                task.find('.badge[class*="prio-"]').attr('class').includes('medium') ? 2 : 3;
            let date = task.find('.date-task').text().trim();

            $('#title').val(title);
            $('#category').val(category);
            $('#priority').val(priority);
            $('#date').val(date);

            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id')
                .attr('id', 'taskId')
                .attr('value', id)
                .appendTo('#taskForm');
        });
    });

    function showError(xhr){
        let html = '<ul>';

        try {
            let response = JSON.parse(xhr.responseText);

            if (response.error && typeof response === 'object') {
                for (let field in response) {
                    if (field === 'error') continue;

                    let errors = response[field];
                    if (Array.isArray(errors)) {
                        html += `<li><strong>${field}:</strong><ul>`;
                        errors.forEach(function (msg) {
                            html += `<li>${msg}</li>`;
                        });
                        html += '</ul></li>';
                    }
                }
            } else {
                html += '<li>Невідома помилка</li>';
            }
        } catch (e) {
            html += '<li>Помилка при обробці відповіді сервера</li>';
        }

        html += '</ul>';

        $('#error').html(html).css({
            color: 'red',
            border: '1px solid #ff7b7b',
            padding: '10px',
            background: '#ffe6e6',
            borderRadius: '5px'
        }).fadeIn(200);
    }

    $(function () {
        $('button[type="reset"]').on('click', function (e) {
            e.preventDefault(); // щоб не скидало форму стандартно

            // Отримуємо чистий URL без параметрів
            let cleanUrl = window.location.origin + window.location.pathname;

            // Перенаправляємо без параметрів
            window.location.href = cleanUrl;
        });
    });

</script>
</body>
</html>
