<?php

require_once('underscore.php');

define('OPT_SQL_FILENAME', 'onemovechess.db');


function main() {
    if (rq('fn')=='board') {
        $bid = getBoard();
    }
}

function getBoard() {
    $___TODO___CanFindSuitableBoard = false;
    $boardId = 0;

    if ($___TODO___CanFindSuitableBoard) {
        $boardId = 1; // this will be an existing board
    } else {
        $boardId = makeNewBoard();
    }

    $board = getBoard($boardId);
}

function boardToClientPiece($boardPiece, $turn) {
    
    $player = $turn % 2;
    $pref   = substr($boardPiece, 0, 1);
    $piece  = substr($boardPiece, 1);

    // figure out what the new prefix will be
    $desiredPref = ($player==0) ? 'w' : 'b';
    $newPref = ($desiredPref == $pref) ? 'my' : 'op';

    // return new name (with new prefix)
    if (in_array($piece, getPieceNames())) {
        return $newPref . $piece;
    }
    return 'ERROR';

}

#     #
#     # ###### #      #####  ###### #####   ####
#     # #      #      #    # #      #    # #
####### #####  #      #    # #####  #    #  ####
#     # #      #      #####  #      #####       #
#     # #      #      #      #      #   #  #    #
#     # ###### ###### #      ###### #    #  ####

function getPieceNames() {
    static $pieceNames = array(
          'King'
        , 'Queen'
        , 'Rook1'
        , 'Rook2'
        , 'Bishop1'
        , 'Bishop2'
        , 'Knight1'
        , 'Knight2'
        , 'Pawn1'
        , 'Pawn2'
        , 'Pawn3'
        , 'Pawn4'
        , 'Pawn5'
        , 'Pawn6'
        , 'Pawn7'
        , 'Pawn8'
    );
    return $pieceNames;
}

function rq($key) {
    if (isset($_REQUEST[$key]))
        return $_REQUEST[$key];
    return null;
}

######
#     #   ##   #####   ##   #####    ##    ####  ######
#     #  #  #    #    #  #  #    #  #  #  #      #
#     # #    #   #   #    # #####  #    #  ####  #####
#     # ######   #   ###### #    # ######      # #
#     # #    #   #   #    # #    # #    # #    # #
######  #    #   #   #    # #####  #    #  ####  ######


function dbConnect() {

    static $db;
    if (isset($db)) return $db;

    if (!file_exists(OPT_SQL_FILENAME)) {
        dbCreateTables();
    }
    if ($db = new PDO('sqlite:'.OPT_SQL_FILENAME)) {
        return $db;
    } else {
        die('Could not create db');
    }
}

function getBoard($id) {
    $db = dbConnect();
    $q=$db->prepare('SELECT * FROM tblGames WHERE id=?');
    $q->execute(array($id));
    $data = $q->fetch();
    return $data;
}

function dbMakeNewBoard() {
    $db = dbConnect();
    $q=$db->prepare('INSERT INTO tblGames (
              turn
            , wKing, wQueen, wRook1, wRook2
            , wBishop1, wBishop2, wKnight1, wKnight2
            , wPawn1, wPawn2, wPawn3, wPawn4, wPawn5, wPawn6, wPawn7, wPawn8
            , bKing, bQueen, bRook1, bRook2
            , bBishop1, bBishop2, bKnight1, bKnight2
            , bPawn1, bPawn2, bPawn3, bPawn4, bPawn5, bPawn6, bPawn7, bPawn8
        ) VALUES (
            1
            , 59, 60, 56, 63
            , 58, 61, 57, 62
            , 48, 49, 50, 51, 52, 53, 54, 55
            ,  3,  4,  0,  7
            ,  2,  5,  1,  6
            ,  8,  9, 10, 11, 12, 13, 14, 15
        );');
    $q->execute();
    return $db->lastInsertId();
}

function dbCreateTables() {
    $db = new SQLite3(OPT_SQL_FILENAME);

    $db->exec('
        CREATE TABLE IF NOT EXISTS tblGames (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,

            turn       INTEGER DEFAULT 0,

            wKing      INTEGER,
            wQueen     INTEGER,
            wRook1     INTEGER,
            wRook2     INTEGER,
            wBishop1   INTEGER,
            wBishop2   INTEGER,
            wKnight1   INTEGER,
            wKnight2   INTEGER,
            wPawn1     INTEGER,
            wPawn2     INTEGER,
            wPawn3     INTEGER,
            wPawn4     INTEGER,
            wPawn5     INTEGER,
            wPawn6     INTEGER,
            wPawn7     INTEGER,
            wPawn8     INTEGER,

            bKing      INTEGER,
            bQueen     INTEGER,
            bRook1     INTEGER,
            bRook2     INTEGER,
            bBishop1   INTEGER,
            bBishop2   INTEGER,
            bKnight1   INTEGER,
            bKnight2   INTEGER,
            bPawn1     INTEGER,
            bPawn2     INTEGER,
            bPawn3     INTEGER,
            bPawn4     INTEGER,
            bPawn5     INTEGER,
            bPawn6     INTEGER,
            bPawn7     INTEGER,
            bPawn8     INTEGER
            
        );');
    $db->close();
}

main();

?>