# 프로시저 목록 확인 ----------------------------------
SHOW PROCEDURE STATUS WHERE DB = 'future';

# 프로시저 생성 ----------------------------------------
DELIMITER //
CREATE PROCEDURE btnIncSet (
    TB_NM VARCHAR(255) , IDX INTEGER
)
BEGIN

update TB_NM set btn_orderby = 2 where btn_idx = IDX;

END
//
DELIMITER ;

# 프로시저 호출 ---------------------------------------
CALL btnIncSet();

# 프로시저 내용 확인 ----------------------------------
SHOW CREATE PROCEDURE btnIncSet;

# 프로시저 삭제 ---------------------------------------
OP PROCEDURE btnIncSet;
