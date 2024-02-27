import React from "react";
import { useNavigate } from "react-router-dom";
import { styled } from "styled-components";
import { Display, Img, Title } from "../../styles/styledComponent";
import IconProfile from "../../assets/image/profile.png";
import IconRight from "../../assets/image/arrow_r_b.png";
import IconTime from "../../assets/image/period.png";
import { Hour, Minute, Time } from "../../utils/TimeCalculation";

import dayjs from "dayjs";

const ReservationCard = ({ data, reservationStt, storeId }) => {
  const navigate = useNavigate();
  const sttValue = data.reservation_stt;
  const sttCss = sttValue === 2;
  const time = data?.reservation_time;
  const hour = Hour(time);
  const minute = Minute(time);

  const period =
    data.reservation_period === 1
      ? 30
      : data.reservation_period === 2
      ? 60
      : 90;
  const endtime = dayjs()
    .set("hour", hour)
    .set("minute", minute)
    .add(period, "m");

  function sttTitle(index) {
    switch (index) {
      case 1:
        return "예약 대기";
      case 2:
        return "예약 확정";
      case 3:
        return "예약 취소";
      case 4:
        return "노쇼";
    }
  }

  const onClickStt = (idx, stt) => {
    const data = { idx, stt };
    reservationStt(data);
    // setLength({idx: idx, stt: stt});
  };

  return (
    <Card>
      <button
        className="btnDetail"
        onClick={() =>
          navigate(`/reservationdetail/${data?.reservation_idx}`, {
            state: { id: storeId, page: `/reservation/${storeId}` },
          })
        }
      >
        <Title size={15}>예약 상세보기</Title>
        <Img src={IconRight} width={13} />
      </button>
      <div className="cardInfo">
        <Display content="space-between">
          <Display>
            <Img
              src={
                !!data?.mb_profile_img
                  ? { uri: data?.mb_profile_img }
                  : IconProfile
              }
              width={60}
            />
            <Title left={10}>{data.reservation_user_name}</Title>
          </Display>
          <Stt
            bg={sttCss ? "#FFE8E8" : "#fff"}
            color={sttCss ? "#FFE8E8" : "#e9e9e9"}
          >
            <Title color={sttCss ? "#F33562" : "#444"} size={14}>
              {sttTitle(sttValue)}
            </Title>
          </Stt>
        </Display>
        <div className="timeBox">
          <div className="flex">
            <Title size={18}>{data.reservation_room_idx}번방</Title>
          </div>

          <div className="time">
            <Text size={18}>{Time(data.reservation_time)}</Text>
            <Img src={IconTime} width={70} height={10} />
            <Arrow>
              <Title size={14}>({period}분)</Title>
            </Arrow>
            <Text size={18}>{dayjs(endtime).format("hh:mm")}</Text>
          </div>
        </div>
        {sttValue === 1 ? (
          <BtnWrap>
            <Box border={"1px solid #e9e9e9"} className="flex">
              <button
                onClick={(e) => {
                  onClickStt(data?.reservation_idx, "cancel");
                }}
              >
                <Title>거절하기</Title>
              </button>
            </Box>
            <div className="flex">
              <button
                onClick={(e) => {
                  onClickStt(data?.reservation_idx, "confirm");
                }}
              >
                <Title color="#F33562">확정하기</Title>
              </button>
            </div>
          </BtnWrap>
        ) : (
          ""
        )}
      </div>
    </Card>
  );
};

const Card = styled.div`
  width: 100%;
  background-color: #fff;
  border-radius: 7px;
  margin: 10px 0px 25px 0px;
  box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;

  z-index: 200;

  & .btnDetail {
    width: 100%;
    height: 50px;
    padding: 0px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-sizing: border-box;

    border-bottom: 1px solid #e9e9e9;
  }

  & .cardInfo {
    padding: 20px 10px 10px 10px;
  }

  & .timeBox {
    height: 65px;
    border-radius: 10px;
    border: 1px solid #e9e9e9;
    padding: 10px 0px;
    box-sizing: border-box;
    margin: 20px 0px;

    display: grid;
    grid-template-columns: 20% 80%;
    justify-content: center;
    align-items: center;
  }

  & .time {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-left: 1px solid #e9e9e9;
    position: relative;
  }

  & .flex {
    display: flex;
    align-items: center;
    justify-content: center;
  }
`;

const Stt = styled.div`
  background-color: ${(props) => props.bg || "#fff"};
  padding: 5px 10px;
  border-radius: 50px;
  border: 1px solid;
  border-color: ${(props) => props.color};
`;

const Box = styled.div`
  border-right: ${(props) => props.border || "none"};
`;

const Arrow = styled.div`
  position: absolute;
  top: 25px;
`;

const BtnWrap = styled.div`
  width: 100%;
  height: 50px;
  margin-top: 35px;
  padding-top: 7px;
  border-top: 1px solid #e9e9e9;

  display: grid;
  grid-template-columns: 1fr 1fr;
`;

const Text = styled(Title)`
  color: "#222";
  padding: 0px 15px;
`;

export default ReservationCard;
