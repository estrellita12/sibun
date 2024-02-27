import React, { useEffect, useState } from "react";
import { useLocation, useNavigate, useParams } from "react-router-dom";
import apis from "../api/apis";
import { ReservationStt } from "../utils/ReservationStt";
import DetailHeader from "../components/Header/DetailHeader";
import {
  Container,
  Img,
  Title,
  Display,
  Btn,
  BtnWrap,
} from "../styles/styledComponent";
import { styled } from "styled-components";

import IconReady from "../assets/image/ready.png";
import IconCancel from "../assets/image/cancel.png";
import IconConfirm from "../assets/image/confirm.png";
import IconNowShow from "../assets/image/noshow.png";
import IconEnter from "../assets/image/enter.png";
import {
  Hour,
  Minute,
  Period,
  TimeSetting,
  Time,
} from "../utils/TimeCalculation";
import BottomBtn from "../components/BottomBtn/BottomBtn";
import { ChangeStt } from "../utils/ReservationStt";

const ReservationDetailScreen = () => {
  const navigate = useNavigate();
  const { state } = useLocation();
  const { id } = useParams();
  const [data, setData] = useState();
  const [update, setUpdate] = useState(null);

  useEffect(() => {
    state &&
      apis.getReservationaDetail(state.id, id).then((res) => {
        if (res.data.result === "000") {
          setData(res.data.data);
        }
      });
    if (!state) {
      navigate(-1);
    }
  }, [update]);

  const stt = Number(data?.reservation_stt);
  const hour = Hour(data?.reservation_time);
  const minute = Minute(data?.reservation_time);
  const endTime = TimeSetting(hour, minute, Period(data?.reservation_period));
  const startTime = Time(data?.reservation_time);

  function sttValue(index) {
    switch (Number(index)) {
      case 1:
        return IconReady;
      case 2:
        return IconConfirm;
      case 3:
        return IconCancel;
      case 4:
        return IconNowShow;
      case 5:
        return IconEnter;
    }
  }

  const onClickStt = async (idx, stt) => {
    const result = await ChangeStt(idx, stt);
    setTimeout(() => (result === "000" ? setUpdate(idx) : ""), 1000);
  };

  const onClickPrev = () => {
    navigate(state.page, { state: state.day });
  };

  return (
    <DetailWrap>
      <div>
        <DetailHeader
          title="예약 상세"
          navigation={navigate}
          onClickClose={onClickPrev}
        />
      </div>

      {data && (
        <div className="sort">
          <div className="iconWrap">
            <Img width={75} bottom={10} src={sttValue(stt)} />
            <Title size={18} color={stt === 3 ? "#444" : "#F33562"}>
              {ReservationStt(stt) === "대기"
                ? "대기 중인 예약입니다."
                : `${ReservationStt(stt)}된 예약입니다.`}
            </Title>
          </div>

          <div className="infoGrid" border={"#b8b8b8"}>
            <div className="flex">
              <span>예약날짜</span>
              <Title size={15} color={"#444"}>
                {data?.reservation_date}
              </Title>
            </div>
            <div className="flex">
              <span>예약시간</span>
              <Title size={15} color={"#444"}>
                {startTime} ~ {endTime}
              </Title>
            </div>
            <div className="flex">
              <span>방 번호</span>
              <Title size={15} color={"#444"}>
                {data?.reservation_room_idx}번
              </Title>
            </div>
          </div>

          <div className="userInfo">
            <Title size={17} color={"#222"} weigth={700}>
              예약자 정보
            </Title>
            <div className="padding">
              <Display content="space-between">
                <span size={15} color="#7d7d7d">
                  예약자명
                </span>
                <Title size={15} color={"#444"}>
                  {data?.reservation_user_name}
                </Title>
              </Display>
              <Display content="space-between" top={10}>
                <span size={15} color="#7d7d7d">
                  전화번호
                </span>
                <button>
                  <Title size={15} color={"#444"}>
                    {data?.reservation_user_cellphone}
                  </Title>
                </button>
              </Display>
            </div>
          </div>
        </div>
      )}
      <div className="btnwrap">
        {stt === 1 ? (
          <BtnWrap>
            <Btn bg={"#D7D7D7"} color={"#444"} onClick={() => onClickPrev()}>
              취소
            </Btn>
            <Btn onClick={() => onClickStt(id, "confirm")}>예약 확정</Btn>
          </BtnWrap>
        ) : (
          <BottomBtn title={"확인"} onClick={onClickPrev} />
        )}
      </div>
    </DetailWrap>
  );
};

const DetailWrap = styled(Container)`
  & .iconWrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 30px;
  }

  & .sort {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 50px 20px;

    box-sizing: border-box;
  }

  & .infoGrid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    border: 1px solid #b8b8b8;
    border-radius: 10px;
    margin-top: 30px;
  }

  & .flex {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-evenly;
    border-right: 1px solid #b8b8b8;
    padding: 20px 0px;
    &:last-child {
      border: none;
    }
  }

  & .userInfo {
    border: 1px solid #efefef;
    border-radius: 10px;
    padding: 20px;
    margin-top: 30px;
  }
  & .padding {
    padding: 20px 0px 20px 10px;
  }

  & .btnwrap {
    width: 100%;
  }
`;

const Box = styled.div``;

export default ReservationDetailScreen;
