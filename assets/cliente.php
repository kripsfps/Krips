<?php
        session_start();

        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header('Location: index.php');
            exit();
        }

        include 'app-discord.php';
        discord();
        extract($_SESSION['userData']);

        $discord_id = $_SESSION['userData']['discord_id'];
        $name = $_SESSION['userData']['username'];

        $servername = "localhost";
        $username = "kripsfps_root";
        $password = "AWwKDI[MG&#-";
        $database = "kripsfps_db"; 

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT server FROM users WHERE discordid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $discord_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $tickets_sql = "SELECT ticketid, pacote, pagamento, custo, estado FROM tickets WHERE discordid = ?";
        $tickets_stmt = $conn->prepare($tickets_sql);
        $tickets_stmt->bind_param("i", $discord_id);
        $tickets_stmt->execute();   
        $tickets_stmt->store_result();

        $tickets_stmt->bind_result($ticketid, $pacote, $pagamento, $custo, $estado);

        include 'assets/components/head.php';

        echo"
        <body class='bg-black fonts'>

        <div id='content' class=' h-screen'>

        <div id='content2' class='flex items-center justify-center'>
            <div
                class='h-full w-full color absolute -top-1/2 flex align-middle animate__animated animate__fadeInDown animate__delay-1s'>
            </div>
        </div>

        ";

        include 'assets/components/header.php';

        echo"
        <section class='h-4/5 flex flex-col items-center justify-center text-center p-4'>
                    <h1 class='text-3xl h-10 md:h-20 md:text-6xl font-bold bg-gradient-to-t from-blue-300 to-blue-500 text-transparent bg-clip-text animate__animated animate__fadeInDown text-blue-500'>
                        Bem vindo, <span class='text-white'>$name</span>
                    </h1>
                    <p class='text-lg w-full md:w-1/2 mb-6 animate__animated animate__fadeInUp text-white opacity-80'></p>
                    <div class='space-x-4 animate__animated animate__fadeInUp'>
                        <div class='max-w-screen-xl px-4 sm:px-6 lg:px-8'>
                            <div class='grid grid-cols-1 gap-4 justify-items-center'>";

        if ($tickets_stmt->num_rows > 0) {
            while ($tickets_stmt->fetch()) {

                if ($row['server'] == 1) {
                    ticket($ticketid);
                }else{ discord();}

                echo "
                <div class='relative w-full'>
                    <div class='absolute inset-0 bg-blue-500 rounded-lg blur-sm'></div>
                    <div class='divide-y divide-gray-200 w-full relative bg-black border-blue-500 border-solid border text-blue-500 font-bold py-2 px-4 rounded-lg'>
                        <div class='p-6 sm:px-8'>
                            <h2 class='text-lg font-bold text-sky-500'>Ticket #$ticketid</h2>
                            <p class='mt-2 text-blue-500 flex justify-start'>Pacote escolhido:<span class='ml-2 text-white'>$pacote</span></p>
                            <p class='mt-2 text-blue-500 flex justify-start'>Metodo de pagamento:<span class='ml-2 text-white'>$pagamento</span></p>
                            <p class='mt-2 text-blue-500 flex justify-start'>Custo Final:<span class='ml-2 text-white'>". htmlspecialchars($custo) ."</span></p>
                            <br>
                            <p class='mt-2 text-blue-500 flex justify-start'>Estado do pedido:<span class='ml-2 text-red-600'>$estado</span></p>
                            <a class='mt-4 block rounded border border-sky-500 bg-gradient-to-t from-blue-300 to-blue-500 px-12 py-3 text-center text-sm font-medium text-white hover:bg-transparent hover:text-white focus:outline-none focus:ring active:text-sky-500 sm:mt-6'
                                href='https://discord.com/invite/kripsoptimization'>Entrar no discord</a>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<p class='text-white font-xl'>Não tens tickets...</p>";
        }

        echo "
                            </div>
                        </div>
                    </div>
                </section>
        ";

        include 'assets/components/footer.php';

        echo"
        <script src='assets/js/pxwg_was_were.js'></script>

        </body>

        </html>
        ";
        ?>