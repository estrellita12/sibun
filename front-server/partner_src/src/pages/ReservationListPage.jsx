import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import apis from "../api/apis";

import styled, { keyframes } from "styled-components";
import { Container, Title } from "../styles/styledComponent";

import NavigationHeader from "../components/Header/NavigationHeader";
import ReservationCard from "../components/Reservation/ReservationCard";
import NavBar from "../components/NavBar/NavBar";
import { ChangeStt } from "../utils/ReservationStt";

const ReservationListPage = () => {
  const navigate = useNavigate();
  const id = localStorage.getItem("storeId");

  const navData = [
    { title: "대기중" },
    { title: "확정" },
    { title: "취소/노쇼" },
  ];
  const [sttCount, setSttCount] = useState();
  const [sttLists, setSttLists] = useState();
  const [navValue, setNavValue] = useState(1);
  const [changeStt, setChangeStt] = useState();

  function getReservationStt() {
    const stt = navValue === 3 ? "3,4" : navValue;
    apis.getReservationSttList(id, stt).then((res) => {
      if (res.data.result === "000") {
        setSttLists(res.data.list);
      } else {
        setSttLists(null);
      }
    });
  }

  function arrLength(groupValues, value) {
    return !groupValues[value] ? 0 : groupValues[value]?.length;
  }

  function getReservation() {
    apis.getReservation(id).then((res) => {
      if (res.data.result === "000") {
        const list = res.data.list;
        const groupValues = list.reduce((acc, current) => {
          acc[current.reservation_stt] = acc[current.reservation_stt] || [];
          acc[current.reservation_stt].push(current);
          return acc;
        }, {});

        setSttCount({
          1: arrLength(groupValues, 1),
          2: arrLength(groupValues, 2),
          3: arrLength(groupValues, 3) + arrLength(groupValues, 4),
        });
        getReservationStt();
      } else {
        setSttCount({ 1: 0, 2: 0, 3: 0 });
      }
    });
  }

  useEffect(() => {
    getReservation();
  }, [changeStt]);

  useEffect(() => {
    getReservationStt();
  }, [navValue]);

  const onClickStt = async (data) => {
    const result = await ChangeStt(data.idx, data.stt);
    setTimeout(() => (result === "000" ? setChangeStt(data.idx) : ""), 1000);
  };

  const onClickNav = (value) => {
    setNavValue(value + 1);
  };

  return (
    <Main>
      <NavigationHeader title={"예약 목록"} value={"nav"} />
      {sttCount && (
        <NavBar
          navData={navData}
          onClickNav={onClickNav}
          navValue={navValue - 1}
          arrLength={sttCount}
          screen={"main"}
          storeId={id}
        />
      )}

      <div className="bg">
        {sttLists &&
          Object.keys(sttLists)?.map((date, idx) => {
            return (
              <div key={idx} className="card">
                <Title bottom={30}>{date}</Title>
                {sttLists[date].map((stt, k) => {
                  return (
                    <ReservationCard
                      data={stt}
                      key={k}
                      reservationStt={onClickStt}
                      storeId={id}
                    />
                  );
                })}
              </div>
            );
          })}
      </div>
    </Main>
  );
};

const Main = styled.div`
  padding-top: 120px;
  background-color: #f4f4f4;
  height: 100vh;

  & .bg {
    width: 100%;
    height: 100%;
    background-color: #f4f4f4;
    padding-bottom: 80px;
  }

  & .card {
    padding: 15px 30px;
    background-color: #f4f4f4;
  }
`;

export default ReservationListPage;
