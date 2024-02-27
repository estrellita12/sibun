import React from "react";
import { styled } from "styled-components";

import { Img } from "../../styles/styledComponent";
import IconOn from "../../assets/image/check_on.png";
import IconOff from "../../assets/image/check_off.png";

const CheckBox = ({ id, onClickCheck, isCheck, width }) => {
  const onClick = () => {
    onClickCheck(id);
  };
  return (
    <Box onClick={() => onClick()}>
      <Img width={width || 23} src={isCheck ? IconOn : IconOff} />
    </Box>
  );
};

const Box = styled.button`
  z-index: 100;
`;

export default CheckBox;
