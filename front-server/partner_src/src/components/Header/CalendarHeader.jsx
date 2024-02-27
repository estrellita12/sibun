import React, { useState, useEffect } from "react";
import IconMenu from "../../assets/image/menu.png";
import IcnMonth from "../../assets/image/month.png";
import { Img, Title } from "../../styles/styledComponent";
import { styled } from "styled-components";
import { useNavigate } from "react-router-dom";
import SideBar from "../SideBar/SideBar";

const CalendarHeader = ({ getOpenValue, day }) => {
  const navigate = useNavigate();
  const [month, setMonth] = useState(day);
  const [openNav, setOpenNav] = useState(false);

  useEffect(() => {
    setMonth(day);
  }, [day]);

  const onClickOpen = () => {
    getOpenValue();
  };

  return (
    <Wrap>
      <Header>
        <button className="btn" onClick={() => navigate("/navbar")}>
          <Img src={IconMenu} width={30} />
        </button>
        <Title color="#222" size={18} weight="600">
          {Number(month)}월{" "}
        </Title>
        <button onClick={() => onClickOpen()}>
          <Img width={20} height={22} src={IcnMonth} />
        </button>
      </Header>
    </Wrap>
  );
};

const Wrap = styled.div`
  position: relative;
  width: 100%;
  & .nav {
    position: absolute;
    z-index: 500;
    width: 100%;
    height: 100vh;
    background-color: #fff;
  }
`;

const Header = styled.div`
  height: 65px;
  background-color: #ffffff;
  padding: 10px 20px;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  position: relative;

  & .btn {
    display: flex;
  }
`;

const Display = styled.button`
  display: flex;
  flex-direction: row;
  align-items: center;
`;

export default CalendarHeader;
