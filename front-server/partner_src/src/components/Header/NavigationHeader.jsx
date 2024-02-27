import React, { useState } from "react";
import { Img } from "../../styles/styledComponent";
import IconMenu from "../../assets/image/menu.png";
import IconBack from "../../assets/image/arrow_left_b.png";
import styled from "styled-components";
import { useNavigate } from "react-router-dom";
import SideBar from "../SideBar/SideBar";
const NavigationHeader = ({ title, value }) => {
  const navigate = useNavigate();
  const [openNav, setOpenNav] = useState(false);
  const onClickBtn = () => {
    if (value === "back") {
      navigate(-1);
    } else {
      setOpenNav(true);
      navigate("/navbar");
    }
  };

  return (
    <Header>
      <button onClick={() => onClickBtn()} className="btn">
        <Icon
          src={value === "back" ? IconBack : IconMenu}
          width={value === "back" ? 20 : 30}
        />
      </button>
      <Title>{title}</Title>
    </Header>
  );
};

const Header = styled.div`
  z-index: 300;
  position: fixed;
  top: 0px;
  width: 100%;
  max-width: 500px;
  height: 70px;
  background-color: #fff;
  padding: 0px 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-bottom: 1px solid #e9e9e9;
  box-sizing: border-box;
  z-index: 100;
  & .btn {
    display: flex;
    align-items: center;
  }
`;

const Icon = styled(Img)`
  position: absolute;
  left: 10px;
`;

const Title = styled.span`
  display: flex;
  justify-content: center;
  align-items: center;

  font-size: 20px;
  font-weight: 600;
`;

export default NavigationHeader;
