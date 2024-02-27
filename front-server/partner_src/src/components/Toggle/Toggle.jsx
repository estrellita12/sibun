import React, { useState } from "react";
import styled from "styled-components";

const Toggle = ({ isOn, toggleSwitch, id }) => {
  const [toggle, setToggle] = useState(isOn);
  const clickedToggle = () => {
    setToggle(!toggle);
    toggleSwitch(!toggle, id);
  };

  return (
    <div>
      <ToggleBtn
        onClick={clickedToggle}
        toggle={!toggle ? "#7d7d7d" : "#f33562"}
      >
        <Circle className={!toggle ? "left" : "right"} />
      </ToggleBtn>
    </div>
  );
};

const ToggleBtn = styled.button`
  width: 45px;
  height: 25px;
  border-radius: 30px;
  border: none;
  cursor: pointer;
  background-color: ${(props) => props.toggle};
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
`;
const Circle = styled.div`
  background-color: white;
  width: 18px;
  height: 18px;
  border-radius: 50px;
  &.left {
    position: absolute;
    left: 5px;
  }
  &.right {
    position: absolute;
    right: 5px;
  }
`;

export default Toggle;
