import React, { useState, useEffect } from "react";

import { styled } from "styled-components";
import { Title, Box, Img } from "../../styles/styledComponent";
import IconClose from "../../assets/image/close_img.png";

const Option = ({
  getData,
  value,
  modalVisible,
  setModalVisible,
  optionData,
  setOptionData,
  selectedOption,
  setSelectedOption,
}) => {
  const valueCheck = value === "edit";
  const [add, setAdd] = useState(null);

  useEffect(() => {
    if (valueCheck) {
      setSelectedOption(optionData);
    }
  }, []);

  const onClickOption = (e) => {
    const arr = [];
    if (selectedOption === null) {
      arr.push(e);
    } else {
      if (selectedOption?.includes(e)) {
        const filter = selectedOption.filter((el) => el !== e);
        arr.push(...filter);
      } else {
        arr.push(...selectedOption, e);
      }
    }
    setSelectedOption(arr);

    if (!valueCheck) {
      getData("store_amenities", arr.join(","));
    }
  };

  const onClickpush = () => {
    if (!add) {
      alert("키워드를 입력해주세요.");
    } else {
      setOptionData([...optionData, add]);
      if (valueCheck) {
        setSelectedOption([...selectedOption, add]);
      }

      setModalVisible(false);
    }
  };

  return (
    <div>
      {modalVisible ? (
        <Bg>
          <button
            className="closeicon"
            onClick={() => setModalVisible(!modalVisible)}
          >
            x
          </button>
          <Modal>
            <Input
              placeholder="#키워드를 입력해주세요."
              onChange={(e) => setAdd(e.target.value)}
              maxLength={10}
            />
            <Btn
              onClick={() => onClickpush()}
              color={add?.length < 1 ? "#989898" : "#fff"}
            >
              완료
            </Btn>
          </Modal>
        </Bg>
      ) : (
        ""
      )}

      <Options>
        {optionData &&
          optionData?.map((i, k) => {
            const checkValue = selectedOption?.includes(i);
            return (
              <OptionBox
                key={k}
                onClick={() => onClickOption(i)}
                bordercolor={checkValue ? "#f33562" : "#d7d7d7"}
                bg={checkValue ? "rgba(243, 53, 98, 0.05)" : "#FFF"}
              >
                <Title size={13} color={checkValue ? "#f33562" : "#d7d7d7"}>
                  {i}
                </Title>
              </OptionBox>
            );
          })}
      </Options>
    </div>
  );
};

const Options = styled.div`
  width: 100%;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-column-gap: 5px;
`;

const OptionBox = styled(Box)`
  height: 45px;
  border: 1px solid;
  border-color: ${(props) => props.bordercolor};
`;

const Input = styled.input`
  width: 100%;
  height: 60px;
  padding: 0px 0px 0px 20px;

  color: #fff;
  font-size: 16px;
  background-color: transparent;
  border: none;
  z-index: 1000;
  &:focus {
    outline: none;
  }
`;

const Btn = styled.button`
  color: ${(props) => props.color};
  position: relative;
  width: 50px;
`;

const Modal = styled.div`
  width: 30%;
  min-width: 300px;
  border-bottom: 1px solid #fff;
  margin: auto;

  display: flex;
  align-items: center;
  z-index: 300;
`;

const Bg = styled.button`
  position: fixed;
  right: 0;
  left: 0;
  bottom: 0;
  top: 0px;
  background-color: rgba(0, 0, 0, 0.85);
  z-index: 700;
  & .closeicon {
    color: #fff;
    font-size: 50px;
    font-weight: 200;
    position: absolute;
    top: 20px;
    right: 20px;
  }
`;

export default Option;
