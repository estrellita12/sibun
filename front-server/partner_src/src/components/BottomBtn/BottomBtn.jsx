import React from "react";
import { styled } from "styled-components";
import { Btn } from "../../styles/styledComponent";
const BottomBtn = ({ onClick, title }) => {
  return <Button onClick={() => onClick()}>{title}</Button>;
};

const Button = styled(Btn)`
  position: fixed;
  bottom: 0px;
  width: 100%;
  max-width: 500px;
  z-index: 600;
`;
export default BottomBtn;
