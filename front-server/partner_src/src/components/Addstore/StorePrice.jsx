import React, { useState, useEffect } from "react";
import { styled } from "styled-components";

import { Title, Img } from "../../styles/styledComponent";

import IconDown from "../../assets/image/down_g.png";
import IconDelete from "../../assets/image/delete_btn.png";
import Dropdown from "../Dropdown/Dropdown";
import { MakeWeekFun } from "../../utils/MakeWeekFun";

import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";
import apis from "../../api/apis";

const StorePrice = ({ getData, value, storeId }) => {
  const dropdownData = [
    { title: "평일", value: "1,2,3,4,5" },
    { title: "주말", value: "6,7" },
  ];
  const [days, setDays] = useState("요일");
  const [open, setOpen] = useState(false);
  const [priceList, setPriceList] = useState(null);
  const [storePrice, setStorePrice] = useState({
    store_pricing_price: null,
    store_pricing_days: null,
    store_pricing_time: null,
    store_pricing_cnt: null,
  });

  const {
    store_pricing_price,
    store_pricing_time,
    store_pricing_cnt,
    store_pricing_days,
  } = storePrice;

  useEffect(() => {
    if (value === "edit") {
      apis.getPricing(storeId).then((res) => {
        if (res.data.result === "000") {
          setPriceList(res.data.list);
        }
      });
    }
  }, []);

  const onChange = (keyvalue, e) => {
    setStorePrice({
      ...storePrice,
      [keyvalue]: e,
    });
  };

  const onClickDown = () => {
    setOpen(!open);
  };

  const onClickAddList = () => {
    const arr = new Array();
    if (
      !store_pricing_cnt ||
      !store_pricing_price ||
      !store_pricing_days ||
      !store_pricing_time
    ) {
      alert("입력칸을 확인해주세요.");
    } else {
      if (priceList === null) {
        arr.push(storePrice);
      } else {
        arr.push(...priceList, storePrice);
      }
    }
    setPriceList(arr);
    getData("store_pricing_li", arr);
    setStorePrice({
      store_pricing_cnt: null,
      store_pricing_price: null,
      store_pricing_days: null,
      store_pricing_time: null,
    });
    setDays("요일");
  };

  const onClickAdd = () => {
    apis.putPricing(storeId, storePrice).then((res) => {
      if (res.data.result === "000") {
        setPriceList(
          priceList === null ? storePrice : [...priceList, storePrice]
        );
        setStorePrice({
          store_pricing_cnt: null,
          store_pricing_price: null,
          store_pricing_days: null,
          store_pricing_time: null,
        });
        setDays("요일");
      }
    });
  };

  const onClickDelete = (e) => {
    if (value == "edit") {
      apis.deletePricing(storeId, e).then((res) => {
        if (res.data.result === "000") {
          const filterList = priceList?.filter(
            (el) => el.store_pricing_idx !== e
          );
          setPriceList(filterList);
        }
      });
    } else {
      const filterList = priceList?.filter((el, index) => index !== e);
      setPriceList(filterList);
      getData("store_pricing_li", filterList);
    }
  };

  function handleOnDragEnd(result) {
    const currentItem = [...priceList];
    const draggingItemIndex = result.source.index;
    const afterDragItemIndex = result.destination.index;
    const removeItem = currentItem.splice(draggingItemIndex, 1);
    currentItem.splice(afterDragItemIndex, 0, removeItem[0]);
    setPriceList(currentItem);
  }

  const onKeyDown = (e) => {
    if (e === "Enter") {
      if (!!store_pricing_cnt && !!store_pricing_days && !!store_pricing_time) {
        value === "edit" ? onClickAdd() : onClickAddList();
      }
    }
  };

  return (
    <Price>
      <div className="top">
        <span className="addtitle">이용가격</span>
        <button
          className="addbtn"
          onClick={() => {
            value === "edit" ? onClickAdd() : onClickAddList();
          }}
        >
          +추가
        </button>
      </div>

      <div className="pricelist">
        <button onClick={() => onClickDown()}>
          <InputBox width="100%">
            <Title size={14} color={days === "요일" ? "#757575" : "#222"}>
              {days}
            </Title>
            <Img src={IconDown} width={12} />
          </InputBox>
        </button>
        {open ? (
          <div className="dropdown">
            <Dropdown
              data={dropdownData}
              width={100}
              getData={onChange}
              setData={setDays}
              onClickDown={onClickDown}
            />
          </div>
        ) : (
          ""
        )}
        <InputBox>
          <Input
            placeholder="예) 60"
            maxLength={3}
            onChange={(e) => onChange("store_pricing_time", e.target.value)}
            value={store_pricing_time || ""}
            name="store_pricing_time"
          />
          <Title size={14} color="#7d7d7d">
            분
          </Title>
        </InputBox>

        <InputBox>
          <Input
            placeholder="예) 1"
            maxLength={3}
            onChange={(e) => onChange("store_pricing_cnt", e.target.value)}
            value={store_pricing_cnt || ""}
            name="store_pricing_cnt"
          />
          <Title size={14} color="#7d7d7d">
            회
          </Title>
        </InputBox>
        <InputBox>
          <Input
            placeholder="예) 12,000"
            onChange={(e) => onChange("store_pricing_price", e.target.value)}
            value={store_pricing_price || ""}
            name="store_pricing_price"
            onKeyDown={(e) => onKeyDown(e.key)}
          />
          <Title size={14} color="#7d7d7d">
            원
          </Title>
        </InputBox>
      </div>

      <div className="dragcontainer">
        <DragDropContext onDragEnd={handleOnDragEnd}>
          <Droppable droppableId="characters">
            {(provided) => (
              <ul
                className="characters"
                {...provided.droppableProps}
                ref={provided.innerRef}
              >
                {priceList &&
                  priceList?.map((price, k) => {
                    const id = price?.store_pricing_idx;
                    return (
                      <Draggable key={k} draggableId={`id-${k}`} index={k}>
                        {(provided) => (
                          <Item
                            ref={provided.innerRef}
                            {...provided.draggableProps}
                            {...provided.dragHandleProps}
                          >
                            <button
                              onClick={() =>
                                onClickDelete(value === "edit" ? id : k)
                              }
                            >
                              <Img src={IconDelete} width={22} />
                            </button>
                            <Title size={value === "edit" ? 15 : 14}>
                              {MakeWeekFun(price?.store_pricing_days)} /{" "}
                              {price?.store_pricing_time}분 /{" "}
                              {price?.store_pricing_cnt}회 / {""}
                              {Number(
                                price?.store_pricing_price
                              ).toLocaleString()}
                              원
                            </Title>
                          </Item>
                        )}
                      </Draggable>
                    );
                  })}
                {provided.placeholder}
              </ul>
            )}
          </Droppable>
        </DragDropContext>
      </div>
    </Price>
  );
};

const InputBox = styled.div`
  height: 50px;
  border-radius: 5px;
  padding: 5px;
  border-width: 1px;
  border-color: #d7d7d7;
  border-style: solid;

  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-evenly;
`;

const Input = styled.input`
  width: 100%;
  height: 50px;
  border-radius: 5px;

  color: #333;
  border: none;
  box-sizing: border-box;
`;

const Price = styled.div`
  & .top {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  & .pricelist {
    display: grid;
    grid-template-columns: 20% 20% 20% 36%;
    grid-column-gap: 5px;
    position: relative;

    margin-top: 12px;
  }

  & .addtitle {
    font-size: 17px;
    font-weight: 600;
  }

  & .dropdown {
    position: absolute;
    top: 60px;
    width: 20%;
    z-index: 100;
  }

  & .addbtn {
    color: #7d7d7d;
    font-size: 14px;
  }

  & .dragcontainer {
    padding-top: 15px;
  }
`;

const List = styled.div`
  padding: 20px 5px;
  overflow-y: auto;
`;

const Item = styled.li`
  display: flex;
  height: 25px;
`;

export default StorePrice;
