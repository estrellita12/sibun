import React from "react";
import Iconclose from "../../assets/image/close.png";
import { Display, Img, Title } from "../../styles/styledComponent";
import { styled } from "styled-components";

const ScheduleHeader = ({ title, onClickClose }) => {
  return (
    <Header content="space-between">
      <Title size="20" weight="600">
        {title}
      </Title>
      <Btn onClick={() => onClickClose()}>
        <Img src={Iconclose} width={16} />
      </Btn>
    </Header>
  );
};

const Header = styled(Display)`
  height: 60px;
  background-color: #ffffff;
  padding: 0 20px;
  display: flex;
  flex-direction: row;
  justify-content: center;
  border-bottom: 1px solid #e9e9e9;
`;

const Btn = styled.button`
  position: absolute;
  right: 15px;
`;

export default ScheduleHeader;
