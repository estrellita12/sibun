import React from "react";

import { Title } from "../../styles/styledComponent";
import { styled } from "styled-components";
const Dropdown = ({ data, width, setData, getData, onPressDown, align }) => {
  const onPressItem = (data) => {
    getData("store_pricing_days", data?.value);
    setData(data?.title);
    onPressDown();
  };

  return (
    <DropDown width={width}>
      {data?.map((i, k) => {
        return (
          <Item
            align={align}
            activeOpacity={0.8}
            key={k}
            border={k + 1 === data?.length ? 0 : 1}
            onClick={() => onClickItem(i)}
          >
            <Title size={14}>{i.title}</Title>
          </Item>
        );
      })}
    </DropDown>
  );
};

const DropDown = styled(View)`
  width: ${(props) => props.width}%;
  background-color: #ffffff;

  border-width: 1px;

  border-color: #d7d7d7;
  /* border-radius: 5px; */
  border-bottom-left-radius: 5px;
  border-bottom-right-radius: 5px;
  margin-top: 4px;

  position: absolute;
  top: 40px;
  z-index: 1000;
`;

const Item = styled(TouchableOpacity)`
  height: 50px;
  padding: 5px 15px;

  justify-content: center;
  border-bottom-width: ${(props) => props.border}px;
  border-color: #d7d7d7;
`;

export default Dropdown;
