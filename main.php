<!DOCTYPE html>
<html>
<head>
    <title>Perpustakaan</title>
    <style>
        /* CSS untuk mengatur tampilan aplikasi */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5; /* Warna latar belakang lembut */
            color: #333; /* Warna teks yang gelap */
            margin: 0;
            padding: 20px;
        }
        /* mengatur tampilan judul dan sub judul*/
        h1, h2 {
            text-align: center;
            color: #444; /* Warna judul */
        }
        /* Mengatur tampilan wadah utama */
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        /* Mengatur tampilan untuk tiap buku */
        .book {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fff;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        /* Mengatur tampilan form-group */
        .form-group {
            margin-bottom: 10px;
        }
        /* Mengatur label dalam form */
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        /* Mengatur input dalam form */
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        /* Mengatur tampilan tombol */
        .btn {
            padding: 10px 15px;
            border-radius: 5px;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }
        /* Mengatur tampilan tombol khusus */
        .btn-primary {
            background-color: #007BFF;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #333;
        }

        .btn-success {
            background-color: #28a745;
        }
        /* Mengatur tampilan pesan peringatan */
        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        /* Mengatur tampilan pesan informasi dan peringatan */
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang di Perpustakaan</h1>

        <?php
        // Definisi kelas Book, ReferenceBook, dan Library di sini

        class Book {
            private $id;
            private $title;
            private $author;
            private $year;
            private $isBorrowed = false;
            private $borrowDate;
            private $returnDate;

            // Constructor
            public function __construct($id, $title, $author, $year) {
                $this->id = $id;
                $this->title = $title;
                $this->author = $author;
                $this->year = $year;
                $this->isBorrowed = false;
                $this->borrowDate = null;
                $this->returnDate = null;
            }

            // Getter methods
            public function getId() {
                return $this->id;
            }

            public function getTitle() {
                return $this->title;
            }

            public function getAuthor() {
                return $this->author;
            }

            public function getYear() {
                return $this->year;
            }
            // Metode untuk memeriksa apakah buku sedang dipinjam
            public function isBorrowed() {
                return $this->isBorrowed;
            }
            // Metode untuk mendapatkan tanggal peminjaman
            public function getBorrowDate() {
                return $this->borrowDate;
            }
            // Metode untuk mendapatkan tanggal pengembalian
            public function getReturnDate() {
                return $this->returnDate;
            }

            // Method untuk meminjam buku
            public function borrowBook($borrowDate) {
                $this->isBorrowed = true;
                $this->borrowDate = $borrowDate;
                $this->returnDate = date('Y-m-d', strtotime($borrowDate . ' + 14 days'));
            }

            // Method untuk mengembalikan buku
            public function returnBook() {
                $this->isBorrowed = false;
                $this->borrowDate = null;
                $this->returnDate = null;
            }

            // Metode untuk menghitung denda keterlambatan
            public function calculateLateFee($returnDate) {
                // Periksa apakah $this->returnDate tidak null
                if ($this->returnDate === null) {
                    return 0; // Tidak ada denda jika belum ada tanggal jatuh tempo
                }

                // Konversi $returnDate dan $this->returnDate ke timestamp untuk perhitungan
                $returnTimestamp = strtotime($returnDate);
                $dueTimestamp = strtotime($this->returnDate);

                // Periksa apakah tanggal pengembalian valid dan terlambat
                if ($returnTimestamp <= $dueTimestamp) {
                    return 0; // Tidak ada denda jika tidak terlambat atau tanggal pengembalian sebelum tanggal jatuh tempo
                }

                // Hitung jumlah hari keterlambatan
                $lateDays = floor(($returnTimestamp - $dueTimestamp) / (60 * 60 * 24));

                // Hitung denda berdasarkan jumlah hari keterlambatan
                $lateFee = $lateDays * 1000; // Misalnya denda Rp. 1000 per hari keterlambatan

                return $lateFee;
            }
        }

        class ReferenceBook extends Book {
            private $isbn;
            private $publisher;

            public function __construct($id, $title, $author, $year, $isbn, $publisher) {
                parent::__construct($id, $title, $author, $year);
                $this->isbn = $isbn;
                $this->publisher = $publisher;
            }

            // Metode untuk mendapatkan ISBN buku referensi
            public function getISBN() {
                return $this->isbn;
            }

            // Metode untuk mendapatkan penerbit buku referensi
            public function getPublisher() {
                return $this->publisher;
            }
        }

        class Library {
            private $books = [];

            // Metode untuk menambahkan buku ke perpustakaan
            public function addBook(Book $book) {
                $this->books[] = $book;
            }

            // Metode untuk mencetak daftar buku yang tersedia
            public function printAvailableBooks() {
                $output = "";

                foreach ($this->books as $book) {
                    if (!$book->isBorrowed()) {
                        $output .= "<div class='book'>";
                        $output .= "<h3>{$book->getTitle()}</h3>";
                        $output .= "<p>Penulis: {$book->getAuthor()}</p>";
                        $output .= "<p>Tahun: {$book->getYear()}</p>";
                        $output .= "<p>ID Buku: {$book->getId()}</p>";
                        if ($book instanceof ReferenceBook) {
                            $output .= "<p>ISBN: {$book->getISBN()}</p>";
                            $output .= "<p>Penerbit: {$book->getPublisher()}</p>";
                        }
                        $output .= "</div>";
                    }
                }

                return $output;
            }

            // Metode untuk mencari buku berdasarkan kuerinya
            public function searchBooks($query) {
                $output = "";

                foreach ($this->books as $book) {
                    if (stripos($book->getTitle(), $query) !== false || stripos($book->getAuthor(), $query) !== false) {
                        $output .= "<div class='book'>";
                        $output .= "<h3>{$book->getTitle()}</h3>";
                        $output .= "<p>Penulis: {$book->getAuthor()}</p>";
                        $output .= "<p>Tahun: {$book->getYear()}</p>";
                        $output .= "<p>ID Buku: {$book->getId()}</p>";
                        if ($book instanceof ReferenceBook) {
                            $output .= "<p>ISBN: {$book->getISBN()}</p>";
                            $output .= "<p>Penerbit: {$book->getPublisher()}</p>";
                        }
                        $output .= "</div>";
                    }
                }

                return $output;
            }

            // Metode untuk menyortir buku berdasarkan kriteria tertentu
            public function sortBooks($criteria) {
                if ($criteria === "title") {
                    usort($this->books, function($a, $b) {
                        return strcmp($a->getTitle(), $b->getTitle());
                    });
                } elseif ($criteria === "author") {
                    usort($this->books, function($a, $b) {
                        return strcmp($a->getAuthor(), $b->getAuthor());
                    });
                } elseif ($criteria === "year") {
                    usort($this->books, function($a, $b) {
                        return $a->getYear() - $b->getYear();
                    });
                }
            }

            // Metode untuk meminjam buku berdasarkan ID
            public function borrowBook($bookId, $borrowDate) {
                foreach ($this->books as $book) {
                    if ($book->getId() === $bookId && !$book->isBorrowed()) {
                        $book->borrowBook($borrowDate);
                        break;
                    }
                }
            }

            // Metode untuk mengembalikan buku berdasarkan ID
            public function returnBook($bookId, $returnDate) {
                foreach ($this->books as $book) {
                    if ($book->getId() === $bookId && $book->isBorrowed()) {
                        $lateFee = $book->calculateLateFee($returnDate);
                        $book->returnBook();
                        return $lateFee;
                    }
                }
                return 0;
            }

            // metode untuk menghapus buku berdasarkan ID
            public function removeBook($bookId) {
                foreach ($this->books as $key => $book) {
                    if ($book->getId() === $bookId) {
                        unset($this->books[$key]);
                        return "Buku dengan ID $bookId telah dihapus.";
                    }
                }
                return "Buku dengan ID $bookId tidak ditemukan.";
            }

            public function getBorrowedBooks() {
                $borrowedBooks = [];

                foreach ($this->books as $book) {
                    if ($book->isBorrowed()) {
                        $borrowedBooks[] = $book;
                    }
                }

                return $borrowedBooks;
            }
        }

        // Membuat instance Library
        $library = new Library();

        // Tambahkan beberapa buku ke perpustakaan
        $book1 = new Book(1, "The Great Gatsby", "F. Scott Fitzgerald", 1925);
        $book2 = new ReferenceBook(2, "Introduction to Algorithms", "Thomas H. Cormen", 2009, "978-0262033848", "MIT Press");
        $book3 = new Book(3, "To Kill a Mockingbird", "Harper Lee", 1960);
        $book4 = new Book(4, "1984", "George Orwell", 1949);
        $book5 = new ReferenceBook(5, "The Art of Computer Programming", "Donald Knuth", 1968, "978-0201896831", "Addison-Wesley");

        // Tambahkan buku-buku ke perpustakaan
        $library->addBook($book1);
        $library->addBook($book2);
        $library->addBook($book3);
        $library->addBook($book4);
        $library->addBook($book5);

        // Tangani permintaan POST untuk berbagai operasi
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST["sortCriteria"])) {
                $criteria = $_POST["sortCriteria"];
                $library->sortBooks($criteria);
            }

            if (isset($_POST["removeBookId"])) {
                $bookId = intval($_POST["removeBookId"]);
                echo "<div class='alert alert-info'>{$library->removeBook($bookId)}</div>";
            }

            if (isset($_POST["borrowBookId"]) && isset($_POST["borrowDate"])) {
                $bookId = intval($_POST["borrowBookId"]);
                $borrowDate = $_POST["borrowDate"];
                $library->borrowBook($bookId, $borrowDate);
            }

            if (isset($_POST["searchQuery"])) {
                $query = $_POST["searchQuery"];
                echo $library->searchBooks($query);
            }

            if (isset($_POST["returnBookId"]) && isset($_POST["returnDate"])) {
                $bookId = intval($_POST["returnBookId"]);
                $returnDate = $_POST["returnDate"];
                $lateFee = $library->returnBook($bookId, $returnDate);
                echo "<div class='alert alert-warning'>Denda keterlambatan: Rp. " . $lateFee . "</div>";
            }
        }
        ?>

        <div class="form-group">
            <h2>Hapus Buku</h2>
            <form method="POST">
                <input type="number" name="removeBookId" placeholder="ID Buku" required>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>

        <h2>Daftar Buku yang Tersedia</h2>
        <div id="availableBooks">
            <?php echo $library->printAvailableBooks(); ?>
        </div>

        <div class="form-group">
            <h2>Sortir Buku Berdasarkan</h2>
            <form method="POST">
                <button type="submit" class="btn btn-primary" name="sortCriteria" value="title">Judul</button>
                <button type="submit" class="btn btn-primary" name="sortCriteria" value="author">Penulis</button>
                <button type="submit" class="btn btn-primary" name="sortCriteria" value="year">Tahun</button>
            </form>
        </div>

        <div class="form-group">
            <h2>Cari Buku</h2>
            <form method="POST">
                <input type="text" name="searchQuery" placeholder="Cari buku berdasarkan judul atau penulis" required>
                <button type="submit" class="btn btn-secondary">Cari</button>
            </form>
        </div>

        <div class="form-group">
            <h2>Pinjam Buku</h2>
            <form method="POST">
                <input type="number" name="borrowBookId" placeholder="ID Buku" required>
                <input type="date" name="borrowDate" placeholder="Tanggal Peminjaman" required>
                <button type="submit" class="btn btn-success">Pinjam</button>
            </form>
        </div>

        <h2>Buku yang Sedang Dipinjam</h2>
        <div id="borrowedBooks">
            <?php
            // Tampilkan daftar buku yang sedang dipinjam
            $borrowedBooks = $library->getBorrowedBooks();
            foreach ($borrowedBooks as $book) {
                echo "<div class='book'>";
                echo "<h3>{$book->getTitle()}</h3>";
                echo "<p>Penulis: {$book->getAuthor()}</p>";
                echo "<p>Tahun: {$book->getYear()}</p>";
                echo "<p>ID Buku: {$book->getId()}</p>";
                if ($book instanceof ReferenceBook) {
                    echo "<p>ISBN: {$book->getISBN()}</p>";
                    echo "<p>Penerbit: {$book->getPublisher()}</p>";
                }

                // Tampilkan tanggal peminjaman dan pengembalian
                echo "<p>Tanggal Peminjaman: " . ($book->getBorrowDate() ? $book->getBorrowDate() : "Belum dipinjam") . "</p>";
                echo "<p>Tanggal Pengembalian: " . ($book->getReturnDate() ? $book->getReturnDate() : "Belum ditentukan") . "</p>";

                // Tambahkan formulir untuk mengembalikan buku
                echo "<form method='POST'>";
                echo "<input type='hidden' name='returnBookId' value='{$book->getId()}'>";
                echo "<input type='date' name='returnDate' placeholder='Tanggal Pengembalian' required>";
                echo "<button type='submit' name='returnBook' class='btn btn-warning'>Kembalikan</button>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
